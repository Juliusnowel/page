<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$headline     = trim((string) ($attributes['headline'] ?? ''));
$paragraphOne = (string) ($attributes['paragraphOne'] ?? '');

$imageUrl = trim((string) ($attributes['imageUrl'] ?? ''));
$imageAlt = trim((string) ($attributes['imageAlt'] ?? ''));

$imagePosRaw = $attributes['imagePosition'] ?? 'left';
$imagePos    = in_array($imagePosRaw, ['left','right'], true) ? $imagePosRaw : 'left';

$imgRadius = isset($attributes['imageBorderRadius']) && is_numeric($attributes['imageBorderRadius'])
  ? (int) $attributes['imageBorderRadius']
  : 44;

$pos_class = ($imagePos === 'right') ? 'is-img-right' : 'is-img-left';

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'card-2ti ' . $pos_class,
  'style' => '--img-radius:' . esc_attr($imgRadius) . 'px;'
]);

$has_text = (trim(wp_strip_all_tags($paragraphOne)) !== '');
$has_img  = ($imageUrl !== '');

?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="card-2ti__inner">

    <div class="card-2ti__media">
      <?php if ($has_img): ?>
        <figure class="card-2ti__figure">
          <img
            class="card-2ti__img"
            src="<?php echo esc_url($imageUrl); ?>"
            alt="<?php echo esc_attr($imageAlt); ?>"
            loading="lazy"
            decoding="async"
          />
        </figure>
      <?php else: ?>
        <div class="card-2ti__placeholder" aria-hidden="true"></div>
      <?php endif; ?>
    </div>

    <div class="card-2ti__content">
      <?php if ($headline !== ''): ?>
        <h2 class="card-2ti__headline"><?php echo esc_html($headline); ?></h2>
      <?php endif; ?>

      <?php if ($has_text): ?>
        <p class="card-2ti__copy"><?php echo wp_kses_post($paragraphOne); ?></p>
      <?php endif; ?>
    </div>

  </div>
</section>
