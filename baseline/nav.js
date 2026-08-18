/* baseline/nav.js — the only JavaScript in the baseline replica.
   Spec §2: vanilla JS, and only where genuinely needed (mobile nav toggle).

   Progressive enhancement: the mobile menu is open by default so the page works
   with JS disabled. This script marks the document as script-capable, which
   reveals the toggle button and lets CSS honour the [hidden] attribute. */
(function () {
  document.documentElement.className += " js-nav";

  document.addEventListener("DOMContentLoaded", function () {
    var toggle = document.querySelector(".menu-toggle");
    var menu = document.getElementById("mobile-menu");
    if (!toggle || !menu) {
      return;
    }
    menu.hidden = true;
    toggle.setAttribute("aria-expanded", "false");
    toggle.addEventListener("click", function () {
      var open = menu.hidden;
      menu.hidden = !open;
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });
})();
