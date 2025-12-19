<?php
if ( ! defined('ABSPATH') ) { exit; }

$title   = trim((string)($attributes['title'] ?? ''));
$text    = trim((string)($attributes['text'] ?? ''));
$ctaText = trim((string)($attributes['ctaText'] ?? ''));
$ctaUrl  = trim((string)($attributes['ctaUrl'] ?? '#'));

$bgUrl = trim((string)($attributes['bgImageUrl'] ?? ''));
$bgOpacity = isset($attributes['bgOpacity']) && is_numeric($attributes['bgOpacity'])
  ? (float)$attributes['bgOpacity']
  : 0.18;
// clamp opacity (0..1)
$bgOpacity = max(0, min(1, $bgOpacity));

$panelBg = trim((string)($attributes['panelBg'] ?? '#E2E2E2'));
$panelRadius = isset($attributes['panelRadius']) && is_numeric($attributes['panelRadius'])
  ? (int)$attributes['panelRadius']
  : 56;
$panelMaxWidth = isset($attributes['panelMaxWidth']) && is_numeric($attributes['panelMaxWidth'])
  ? (int)$attributes['panelMaxWidth']
  : 1200;

$style_parts = [];
$style_parts[] = '--panel-bg:' . esc_attr($panelBg) . ';';
$style_parts[] = '--panel-radius:' . esc_attr($panelRadius) . 'px;';
$style_parts[] = '--panel-max:' . esc_attr($panelMaxWidth) . 'px;';
$style_parts[] = '--bg-opacity:' . esc_attr($bgOpacity) . ';';
$style_parts[] = $bgUrl !== ''
  ? "--bg-image:url('" . esc_url($bgUrl) . "');"
  : "--bg-image:none;";

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'cta-img-panel',
  'style' => implode('', $style_parts),
]);
?>

<section <?= $wrapper_attributes; ?>>
  <div class="cta-img-panel__card">
    <div class="cta-img-panel__inner">
      <?php if ($title !== ''): ?>
        <h2 class="cta-img-panel__title"><?= esc_html($title); ?></h2>
      <?php endif; ?>

      <?php if ($text !== ''): ?>
        <p class="cta-img-panel__text"><?= esc_html($text); ?></p>
      <?php endif; ?>

      <?php if ($ctaText !== ''): ?>
        <a class="cta-img-panel__btn" href="<?= esc_url($ctaUrl ?: '#'); ?>">
          <?= esc_html($ctaText); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
