/* ============================================================================
   v2/app.js — progressive enhancement only.

   Every page works with JavaScript disabled: the nav is a visible list, the
   form posts to the action attribute in the HTML, and both store badges are
   present and clickable. This file only improves on that:

     1. collapses the nav behind a toggle on small screens
     2. captures utm_source / utm_medium / utm_campaign, persists them for the
        session, writes them into the form's hidden fields, and appends them to
        outbound store links   (spec §6 item 7)
     3. puts the badge matching the visitor's platform first (spec §6 item 3)
     4. submits the lead form without a page reload (the form handler records
        it either way — see initForm)

   No third-party analytics, no trackers, no cookies — spec §8.
   ========================================================================== */
(function () {
  "use strict";

  var cfg = window.MC_CONFIG || {};
  var utmKeys = cfg.UTM_KEYS || ["utm_source", "utm_medium", "utm_campaign"];
  var storeKey = cfg.UTM_STORAGE_KEY || "mc_utm";

  /* The .js class is set by the inline bootstrap script in each page's <head>,
     early enough that CSS can collapse the nav before the first paint. Setting
     it again here is harmless and keeps this file working on its own. */
  if (document.documentElement.className.indexOf("js") === -1) {
    document.documentElement.className += " js";
  }

  /* --- 2. UTM capture ----------------------------------------------------- */

  function readStored() {
    try {
      return JSON.parse(window.sessionStorage.getItem(storeKey)) || {};
    } catch (e) {
      return {};
    }
  }

  function collectUtm() {
    var params = new URLSearchParams(window.location.search);
    var stored = readStored();
    var found = {};
    var fresh = false;

    utmKeys.forEach(function (key) {
      var value = params.get(key);
      if (value) {
        found[key] = value;
        fresh = true;
      }
    });

    /* Parameters on the current URL win; otherwise fall back to whatever the
       visitor arrived with earlier in this session. */
    if (!fresh) {
      return stored;
    }

    try {
      window.sessionStorage.setItem(storeKey, JSON.stringify(found));
    } catch (e) {
      /* private mode / storage disabled — carry on with the in-memory copy */
    }
    return found;
  }

  function fillHiddenFields(utm) {
    utmKeys.forEach(function (key) {
      var input = document.querySelector('input[name="' + key + '"]');
      if (input) {
        input.value = utm[key] || "";
      }
    });
    var landing = document.querySelector('input[name="landing_page"]');
    if (landing) {
      landing.value = window.location.pathname + window.location.search;
    }
  }

  function appendUtmToStoreLinks(utm) {
    var keys = Object.keys(utm);
    if (!keys.length) {
      return;
    }
    var links = document.querySelectorAll("a[data-store-link]");
    Array.prototype.forEach.call(links, function (link) {
      var url;
      try {
        url = new URL(link.href);
      } catch (e) {
        return;
      }
      keys.forEach(function (key) {
        url.searchParams.set(key, utm[key]);
      });
      link.href = url.toString();
    });
  }

  /* --- 3. Platform detection ---------------------------------------------- */

  function detectPlatform() {
    var uaData = navigator.userAgentData;
    var ua = navigator.userAgent || "";

    if (uaData && uaData.platform) {
      var p = uaData.platform.toLowerCase();
      if (p.indexOf("android") > -1) return "android";
      if (p.indexOf("ios") > -1) return "ios";
    }
    if (/android/i.test(ua)) return "android";
    if (/iPad|iPhone|iPod/.test(ua)) return "ios";
    /* iPadOS 13+ reports as Macintosh but reports touch points. */
    if (/Macintosh/.test(ua) && navigator.maxTouchPoints > 1) return "ios";
    return null;
  }

  function orderBadges(platform) {
    if (!platform) {
      return;
    }
    var rows = document.querySelectorAll("[data-store-row]");
    Array.prototype.forEach.call(rows, function (row) {
      var match = row.querySelector('[data-platform="' + platform + '"]');
      if (match && row.firstElementChild !== match) {
        row.insertBefore(match, row.firstElementChild);
      }
    });
  }

  /* --- 1. Nav toggle ------------------------------------------------------ */

  function initNav() {
    var toggle = document.querySelector(".nav-toggle");
    var nav = document.getElementById("site-nav");
    if (!toggle || !nav) {
      return;
    }

    /* Open/closed is a class, and the collapsed state is already applied by CSS
       before first paint (see the .js rules in styles.css). Nothing here moves
       the page after it has been painted. */
    toggle.setAttribute("aria-expanded", "false");
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  /* --- 4. Lead submission ------------------------------------------------ */

  /* Netlify records the submission either way. With JavaScript off the browser
     posts natively and Netlify redirects; the code below only removes the page
     reload from the middle of that, and stashes the visitor's details so the
     thank-you page can greet them and pre-fill their WhatsApp message. If the
     fetch fails for any reason we fall back to a real browser submit rather
     than lose the lead. */
  function initForm() {
    var form = document.querySelector("form[data-lead-form]");
    if (!form) {
      return;
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      var data = new FormData(form);
      var button = form.querySelector('button[type="submit"]');
      var thanks = form.getAttribute("action") || cfg.THANKS_PAGE || "/thanks.html";

      try {
        window.sessionStorage.setItem("mc_lead", JSON.stringify({
          name: data.get("name") || "",
          phone: data.get("phone") || "",
          parish: data.get("parish") || "",
          need: data.get("need") || ""
        }));
      } catch (e) {
        /* storage unavailable — the thank-you page just stays generic */
      }

      if (button) {
        button.disabled = true;
        button.textContent = "Sending\u2026";
      }

      fetch(cfg.FORM_POST_PATH || "/", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(data).toString()
      }).then(function (response) {
        if (!response.ok) {
          throw new Error(String(response.status));
        }
        window.location.href = thanks;
      }).catch(function () {
        form.submit();
      });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    var utm = collectUtm();
    initNav();
    initForm();
    fillHiddenFields(utm);
    appendUtmToStoreLinks(utm);
    orderBadges(detectPlatform());
  });
})();
