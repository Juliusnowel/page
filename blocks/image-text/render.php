<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Image + Text block (vite-child/image-text)
 * Attributes:
 * - textPos: left|right (desktop)
 * - title, text
 * - imageUrl/imageAlt OR mediaUrl/mediaAlt
 * - imageRadius (px)
 */

$textPosRaw = $attributes['textPos'] ?? 'right';
$textPos    = in_array($textPosRaw, ['left','right'], true) ? $textPosRaw : 'right';

$title = trim((string) ($attributes['title'] ?? ''));
$text  = (string) ($attributes['text'] ?? '');

$imageUrl = trim((string) ($attributes['imageUrl'] ?? ''));
$imageAlt = trim((string) ($attributes['imageAlt'] ?? ''));

// Back-compat with your current template attrs
$mediaUrl = trim((string) ($attributes['mediaUrl'] ?? ''));
$mediaAlt = trim((string) ($attributes['mediaAlt'] ?? ''));

$imgUrl = $imageUrl !== '' ? $imageUrl : $mediaUrl;
$imgAlt = $imageAlt !== '' ? $imageAlt : $mediaAlt;

$radius = isset($attributes['imageRadius']) && is_numeric($attributes['imageRadius'])
  ? (int) $attributes['imageRadius']
  : 44;

$align_class = isset($attributes['align']) ? 'align' . $attributes['align'] : '';
$pos_class   = ($textPos === 'left') ? 'is-text-left' : 'is-text-right';

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'image-text child-block ' . $align_class . ' ' . $pos_class,
  'style' => '--img-radius:' . esc_attr($radius) . 'px;'
]);

?>
<section <?= $wrapper_attributes; ?>>
  <div class="image-text__inner">

    <div class="image-text__col image-text__content">
      <?php if ($title !== ''): ?>
        <h2 class="image-text__title"><?= esc_html($title); ?></h2>
      <?php endif; ?>

      <?php if (trim(strip_tags($text)) !== ''): ?>
        <div class="image-text__text"><?= wp_kses_post($text); ?></div>
      <?php endif; ?>
    </div>

    <div class="image-text__col image-text__media">
      <?php if ($imgUrl !== ''): ?>
        <figure class="image-text__figure">
          <img
            class="image-text__img"
            src="<?= esc_url($imgUrl); ?>"
            alt="<?= esc_attr($imgAlt); ?>"
            loading="lazy"
            decoding="async"
          />
        </figure>
      <?php else: ?>
        <div class="image-text__placeholder" aria-hidden="true"></div>
      <?php endif; ?>
    </div>

  </div>
</section>
