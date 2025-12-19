<?php
if ( ! defined('ABSPATH') ) { exit; }

/**
 * vite-child/image-cards-grid
 * attrs:
 * - title (string)
 * - text (string)
 * - images (array of { url, alt })
 */

$title = trim((string)($attributes['title'] ?? ''));
$text  = trim((string)($attributes['text'] ?? ''));

$images_raw = $attributes['images'] ?? [];
$images_raw = is_array($images_raw) ? $images_raw : [];

/** sanitize images */
$images = [];
foreach ($images_raw as $img) {
  if (!is_array($img)) continue;
  $url = trim((string)($img['url'] ?? ''));
  if ($url === '') continue;
  $alt = trim((string)($img['alt'] ?? ''));
  $images[] = ['url' => $url, 'alt' => $alt];
}

/** if empty, show 3 placeholders so you can see the section */
$count = count($images);
if ($count === 0) {
  $count = 3;
}

/** desktop cols: 3 or 4 (cap at 4) */
$cols = max(1, min(4, $count));
$count_class = 'is-count-' . (int)$count;

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'img-cards-grid ' . $count_class,
  'style' => '--cols:' . esc_attr($cols) . ';'
]);
?>

<section <?= $wrapper_attributes; ?>>
  <div class="img-cards-grid__top">
    <?php if ($title !== ''): ?>
      <h2 class="img-cards-grid__title"><?= esc_html($title); ?></h2>
    <?php endif; ?>

    <?php if ($text !== ''): ?>
      <p class="img-cards-grid__text"><?= esc_html($text); ?></p>
    <?php endif; ?>
  </div>

  <div class="img-cards-grid__grid" role="list">
    <?php if (!empty($images)): ?>
      <?php foreach ($images as $i => $img): ?>
        <div class="img-cards-grid__card" role="listitem">
          <img
            class="img-cards-grid__img"
            src="<?= esc_url($img['url']); ?>"
            alt="<?= esc_attr($img['alt']); ?>"
            loading="lazy"
            decoding="async"
          />
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <?php for ($i = 0; $i < $count; $i++): ?>
        <div class="img-cards-grid__card img-cards-grid__card--placeholder" aria-hidden="true"></div>
      <?php endfor; ?>
    <?php endif; ?>
  </div>
</section>
