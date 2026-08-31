(function () {
  var tog = document.getElementById('navtog'), nav = document.getElementById('nav');
  if (tog && nav) {
    tog.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      tog.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }
  // Campaign source tracking: ?src=tiktok-bio, ?src=ad-roadside-01
  try {
    var p = new URLSearchParams(location.search).get('src');
    if (p) sessionStorage.setItem('mc_src', p.replace(/[^a-zA-Z0-9_-]/g, '').slice(0, 40));
    var v = sessionStorage.getItem('mc_src') || 'direct';
    document.querySelectorAll('.js-source').forEach(function (el) { el.value = v; });
  } catch (err) {}
})();
