/* ============================================================================
   v2/config.js — THE ONE PLACE TO CONFIGURE THIS SITE.

   Everything an operator needs to change after handover lives in this object.
   No other file contains an endpoint, a phone number or a store URL that has
   to be edited. Spec §6 item 6 requires the form endpoint to be configurable
   in one place; the rest is here for the same reason.

   NOTE ON THE FORM ENDPOINT
   The form in index.html also carries the endpoint in its own action="..."
   attribute. That is deliberate: it is the no-JavaScript fallback, so the form
   still submits with scripting disabled. app.js overwrites form.action with
   the value below on load, so this file remains the source of truth — but if
   you change FORM_ENDPOINT, change the action attribute in index.html to match
   so the no-JS path posts to the same place.
   ========================================================================== */

window.MC_CONFIG = {
  /* Lead capture runs through submit.php in this same folder. What you may
     want to change:

       FORM_POST_PATH  the handler the browser posts to.
       THANKS_PAGE     where the visitor lands afterwards.

     Leads are appended to _leads/leads.csv and emailed to the address set as
     NOTIFY_TO at the top of submit.php. The CSV is the record; the email is
     only the notification. */
  FORM_POST_PATH: "submit.php",
  THANKS_PAGE: "thanks.html",

  /* Contact routes. */
  WHATSAPP_NUMBER: "18764703144",
  WHATSAPP_MESSAGE: "Hi Mechanic Connect JA, I need a mechanic.",
  CONTACT_EMAIL: "mechanicconnectja@gmail.com",

  /* App listings. Two apps, two audiences — spec §5 note and §6 item 4.
     mechanic.ios is null because no Apple listing for the staff app was
     supplied; nothing is invented to fill the gap. Add the URL here when it
     exists and the iOS badge appears on the mechanics path automatically. */
  STORES: {
    customer: {
      ios: "https://apps.apple.com/jm/app/mechanic-connect/id6754509101",
      android:
        "https://play.google.com/store/apps/details?id=com.mechanic.mechanicconnect"
    },
    mechanic: {
      ios: null,
      android:
        "https://play.google.com/store/apps/details?id=com.mechanic.mechanics"
    }
  },

  /* Query-string keys captured and carried through — spec §6 item 7.
     Nothing beyond this is collected: spec §8 rules out further analytics. */
  UTM_KEYS: ["utm_source", "utm_medium", "utm_campaign"],

  /* sessionStorage key the captured parameters are held under, so a visitor
     who arrives on an ad and then clicks through two pages still carries the
     attribution when they reach the form or the store link. */
  UTM_STORAGE_KEY: "mc_utm"
};
