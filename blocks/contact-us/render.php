<?php
/**
 * Render callback for Contact Us (CF7) block
 */

$attributes = $attributes ?? [];

$title        = $attributes['title']        ?? 'CONTACT US';
$badgeText    = $attributes['badgeText']    ?? 'WE ARE RESPONDING WITHIN 24H';
$subtitle     = $attributes['subtitle']     ?? '';
$cf7Shortcode = $attributes['cf7Shortcode'] ?? '';

$emailLabel = $attributes['emailLabel'] ?? 'EMAIL US';
$emailText  = $attributes['emailText']  ?? 'Our friendly team is here to help.';
$emailValue = $attributes['emailValue'] ?? 'HELLO@VITESEO.COM';

$officeLabel = $attributes['officeLabel'] ?? 'OFFICE';
$officeText  = $attributes['officeText']  ?? 'Come say hello at our office HQ.';
$officeValue = $attributes['officeValue'] ?? "123 INNOVATION DR, MAKATI CITY,\nPHILIPPINES 1200";

$phoneLabel = $attributes['phoneLabel'] ?? 'PHONE';
$phoneText  = $attributes['phoneText']  ?? 'Mon–Fri from 8am to 5pm.';
$phoneValue = $attributes['phoneValue'] ?? '+63 912 345 6789';

$block_id = 'contact-us-' . wp_rand(1000, 9999);

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'contact-us-block alignwide',
  'id'    => $block_id,
]);

?>

<section <?php echo $wrapper_attributes; ?>>
  <div class="contact-us__top">
    <?php if (!empty($badgeText)) : ?>
      <div class="contact-us__badge" aria-label="<?php echo esc_attr($badgeText); ?>">
        <span class="contact-us__badge-dot" aria-hidden="true"></span>
        <span class="contact-us__badge-text"><?php echo esc_html($badgeText); ?></span>
      </div>
    <?php endif; ?>

    <h1 class="contact-us__title"><?php echo esc_html($title); ?></h1>

    <?php if (!empty($subtitle)) : ?>
      <p class="contact-us__subtitle"><?php echo wp_kses_post(nl2br(esc_html($subtitle))); ?></p>
    <?php endif; ?>
  </div>

  <div class="contact-us__grid">
    <!-- LEFT: Form -->
    <div class="contact-us__panel contact-us__panel--form">
      <h2 class="contact-us__panel-title">SEND US A MESSAGE</h2>

      <div class="contact-us__form">
        <?php
          if (!empty($cf7Shortcode)) {
            echo do_shortcode($cf7Shortcode);
          } else {
            echo '<p style="opacity:.8">Please set the Contact Form 7 shortcode in block attributes.</p>';
          }
        ?>
      </div>
    </div>

    <!-- RIGHT: Info -->
    <aside class="contact-us__panel--info" aria-label="Contact details">
      <div class="contact-us__info-card">
        <div class="contact-us__info-item">
          <div class="contact-us__info-ico" aria-hidden="true">
            <i class="fa-regular fa-envelope"></i>
          </div>
          <div class="contact-us__info-body">
            <h3 class="contact-us__info-title"><?php echo esc_html($emailLabel); ?></h3>
            <p class="contact-us__info-text"><?php echo esc_html($emailText); ?></p>
            <p class="contact-us__info-strong"><?php echo esc_html($emailValue); ?></p>
          </div>
        </div>
        <hr>
        <div class="contact-us__info-item">
          <div class="contact-us__info-ico" aria-hidden="true">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <div class="contact-us__info-body">
            <h3 class="contact-us__info-title"><?php echo esc_html($officeLabel); ?></h3>
            <p class="contact-us__info-text"><?php echo esc_html($officeText); ?></p>
            <p class="contact-us__info-strong"><?php echo wp_kses_post(nl2br(esc_html($officeValue))); ?></p>
          </div>
        </div>
        <hr>
        <div class="contact-us__info-item">
          <div class="contact-us__info-ico" aria-hidden="true">
            <i class="fa-solid fa-phone"></i>
          </div>
          <div class="contact-us__info-body">
            <h3 class="contact-us__info-title"><?php echo esc_html($phoneLabel); ?></h3>
            <p class="contact-us__info-text"><?php echo esc_html($phoneText); ?></p>
            <p class="contact-us__info-strong"><?php echo esc_html($phoneValue); ?></p>
          </div>
        </div>

        <!-- <div class="contact-us__social" aria-label="Social links">
          <a class="contact-us__social-btn" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a class="contact-us__social-btn" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a class="contact-us__social-btn" href="#" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
          <a class="contact-us__social-btn" href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
        </div> -->
      </div>
    </aside>

  </div>
</section>
