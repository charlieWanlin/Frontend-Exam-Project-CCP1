<?php
/**
 * Partial : bannière CTA Style Finder
 * Variable optionnelle : $ctaTitle, $ctaSubtitle
 */
$ctaTitle    = $ctaTitle    ?? 'Identifiez un look précis par photo';
$ctaSubtitle = $ctaSubtitle ?? 'Uploadez une photo d\'une scène, notre IA retrouve le vêtement exact';
?>
<section class="cta-dark-banner" aria-label="Style Finder">
  <h2 style="font-family:var(--font-heading); font-size:clamp(1.8rem,4vw,2.8rem); font-weight:600; margin-bottom:.75rem">
    <?= htmlspecialchars($ctaTitle) ?>
  </h2>
  <p style="font-family:var(--font-sans); font-size:.85rem; color:rgba(255,255,255,.45); margin-bottom:2rem">
    <?= htmlspecialchars($ctaSubtitle) ?>
  </p>
  <a href="/style-finder" class="btn-ghost-white">Utiliser le Style Finder →</a>
</section>