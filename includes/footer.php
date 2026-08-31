</main>

<footer class="ftr">
  <div class="wrap">
    <div class="ftr__grid">
      <div>
        <h3>Mechanic Connect JA</h3>
        <p style="font-size:15.5px;color:rgba(255,255,255,.84)">The smartest way to get your auto services. Connecting Jamaican drivers with verified garages and independent mechanics. Powered by OH-PEL AUTO LIMITED.</p>
      </div>
      <div>
        <h3>Contact</h3>
        <ul>
          <li><a href="<?= wa() ?>">WhatsApp <?= substr(WA_NUMBER,1,3) ?>-<?= substr(WA_NUMBER,4,3) ?>-<?= substr(WA_NUMBER,7) ?></a></li>
          <li><a href="mailto:<?= LEAD_EMAIL ?>"><?= LEAD_EMAIL ?></a></li>
          <li><a href="tel:+1<?= str_replace('-','',SUPPORT_PHONE) ?>"><?= SUPPORT_PHONE ?></a></li>
          <li><?= SUPPORT_HOURS ?></li>
          <li>26 Eastwood Park Road<br>Kingston 10, Jamaica</li>
        </ul>
      </div>
      <div>
        <h3>Quick links</h3>
        <ul>
          <li><a href="<?= BASE ?>/roadside">Roadside assistance</a></li>
          <li><a href="<?= BASE ?>/inspection">15-point inspection</a></li>
          <li><a href="<?= BASE ?>/parts">Request a part</a></li>
          <li><a href="<?= BASE ?>/guide">Free guide for drivers</a></li>
          <li><a href="<?= BASE ?>/join">Join as a provider</a></li>
          <li><a href="https://www.instagram.com/mechanicconnectja/">Instagram</a> &middot; <a href="https://www.tiktok.com/@mechanicconnectja">TikTok</a></li>
          <li><a href="<?= BASE ?>/privacy">Privacy</a> &middot; <a href="<?= BASE ?>/terms">Terms</a></li>
        </ul>
      </div>
    </div>
    <div class="fine">
      <p>Service area: Kingston, St. Andrew, Portmore and Spanish Town. Other parishes to follow.</p>
      <p>Support hours shown are for customer support. They are not roadside assistance hours.</p>
      <p style="margin-top:8px">&copy; <?= date('Y') ?> Mechanic Connect JA. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="<?= BASE ?>/assets/js/app.js" defer></script>
</body>
</html>
