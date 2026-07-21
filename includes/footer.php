<?php
/**
 * Site footer, shared by every page. See includes/navbar.php for the
 * $isHome / in-page-section-link rationale (same pattern reused here,
 * redefined locally so this partial doesn't depend on include order).
 */
$isHome = $isHome ?? false;

$navTarget = static function (string $sectionId) use ($isHome): string {
    if ($isHome) {
        return 'href="#' . $sectionId . '" data-scroll="' . $sectionId . '"';
    }

    return 'href="' . e(url('/') . '#' . $sectionId) . '"';
};
?>
<footer class="footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="footer-logo"><img src="<?= e(asset('images/MLogo.png')) ?>" alt="Ministry logo" style="width:50%;height:50%;object-fit:contain;" /></div>
        <p data-i18n="footer_brand">Ministry of Information Technology, Communication and Innovation - Republic of Mauritius. Building a smarter, fairer future with AI.</p>
      </div>
      <div class="footer-col"><h4 data-i18n="footer_nav">Navigation</h4><ul>
        <li><a <?= $navTarget('about-combined') ?> data-i18n="footer_about">About Us</a></li>
        <li><a <?= $navTarget('team') ?> data-i18n="footer_team">Meet the Team</a></li>
        <li><a <?= $navTarget('action') ?> data-i18n="footer_action">AI in Action</a></li>
        <li><a <?= $navTarget('strategy') ?> data-i18n="footer_framework">AI Framework</a></li>
      </ul></div>
      <div class="footer-col"><h4 data-i18n="footer_resources">Resources</h4><ul>
        <li><a href="<?= e(url('document/aistrategy')) ?>" target="_blank" rel="noopener" data-i18n="footer_strategy">National AI Strategy</a></li>
        <li><a href="<?= e(url('document/fairguidelines')) ?>" target="_blank" rel="noopener" data-i18n="footer_fair">FAIR Guidelines</a></li>
        <li><a href="<?= e(url('document/blueprint')) ?>" target="_blank" rel="noopener" data-i18n="footer_blueprint">Digital Blueprint</a></li>
        <li><a href="<?= e(url('document/playbook')) ?>" target="_blank" rel="noopener" data-i18n="footer_playbook">AI Playbook</a></li>
      </ul></div>
      <div class="footer-col"><h4 data-i18n="footer_info">Information</h4><ul>
        <li><a href="<?= e(url('privacy-policy')) ?>" data-i18n="footer_privacy">Privacy Policy</a></li>
        <li><a href="<?= e(url('disclaimer')) ?>" data-i18n="footer_disclaimer">Disclaimer</a></li>
        <li><a href="<?= e(url('cookie-policy')) ?>" data-i18n="footer_cookie">Cookie Policy</a></li>
        <li><a href="<?= e(url('accessibility')) ?>" data-i18n="footer_accessibility">Accessibility Statement</a></li>
        <li><a <?= $navTarget('contact') ?> data-i18n="footer_contact">Connect with us</a></li>
      </ul></div>
    </div>
    <div class="footer-disclaimer">
      <p data-i18n="footer_disclaimer_text">The Regional AI Marketplace is a facilitation tool. Listing a company or solution does not constitute an official government endorsement, certification, or guarantee of quality by the Ministry of Information Technology, Communication and Innovation or the Government of Mauritius. Users are encouraged to conduct their own due diligence before entering into technical or financial agreements.</p>
    </div>
    <div class="footer-bottom">
      <p data-i18n="footer_copyright">© 2026 Artificial Intelligence Unit, Republic of Mauritius. Developed and Hosted by Government Online Centre.</p>
    </div>
  </div>
</footer>
