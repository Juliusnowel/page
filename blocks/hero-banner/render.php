<?php
if (!defined('ABSPATH')) { exit; }

$attrs = wp_parse_args(
  is_array($attributes ?? null) ? $attributes : [],
  [
    'title'       => '',
    'highlight'   => '',
    'description' => '',
    'badge'       => '',
  ]
);

$title       = trim((string) $attrs['title']);
$highlight   = trim((string) $attrs['highlight']);
$description = trim((string) $attrs['description']);
$badge       = trim((string) $attrs['badge']);

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'hero-banner-block position-relative overflow-hidden'
]);
?>

<section <?php echo $wrapper_attributes; ?>>
  <div class="hero-liquid-bg-wrapper" aria-hidden="true">
    <div class="blob blob-left"></div>
    <div class="blob blob-right"></div>
  </div>

  <div class="hero-banner__container container-fluid position-relative z-1">
    <div class="row justify-content-center text-center">
      <div class="col-12">
        <?php if ($badge !== ''): ?>
          <div class="d-inline-block mb-4">
            <span class="badge rounded-pill px-3 py-2"><?php echo esc_html($badge); ?></span>
          </div>
        <?php endif; ?>

        <?php if ($title !== '' || $highlight !== ''): ?>
          <h1 class="display-1 text-white mb-2 lh-1">
            <?php echo esc_html($title); ?>
            <?php if ($highlight !== ''): ?><br>
              <span class="text-white fst-normal"><?php echo esc_html($highlight); ?></span>
            <?php endif; ?>
          </h1>
        <?php endif; ?>

        <?php if ($description !== ''): ?>
          <p class="lead text-light opacity-75 mx-auto mb-5 hero-banner__lead">
            <?php echo esc_html($description); ?>
          </p>
        <?php endif; ?>

        <a href="#" class="btn btn-light rounded-pill px-5 py-3 fw-bold mb-5">
          START YOUR PROJECT <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const heroBlock = document.querySelector('.hero-banner-block');
      if (!heroBlock) return;

      const blobs = heroBlock.querySelectorAll('.blob');
      let mouseX = 0, mouseY = 0, time = 0;
      let rafId = null;

      const blobConfigs = [
        { speed: 0.0008, amplitudeX: 60, amplitudeY: 50, scaleRange: 0.10, phase: 0,        initialX: -20, initialY: -10 },
        { speed: 0.0006, amplitudeX: 70, amplitudeY: 60, scaleRange: 0.12, phase: Math.PI, initialX:  30, initialY:  15 }
      ];

      document.addEventListener('mousemove', function (e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
      });

      function animate() {
        time += 16;

        blobs.forEach((blob, index) => {
          const config = blobConfigs[index] || blobConfigs[0];
          const rect = blob.getBoundingClientRect();

          const blobCenterX = rect.left + rect.width / 2;
          const blobCenterY = rect.top + rect.height / 2;

          const waveX =
            Math.sin(time * config.speed + config.phase) * config.amplitudeX +
            Math.cos(time * config.speed * 1.3) * (config.amplitudeX * 0.5);

          const waveY =
            Math.cos(time * config.speed * 0.8 + config.phase) * config.amplitudeY +
            Math.sin(time * config.speed * 1.1) * (config.amplitudeY * 0.6);

          const waveScale = 1 + Math.sin(time * config.speed * 0.7) * config.scaleRange;

          const deltaX = mouseX - blobCenterX;
          const deltaY = mouseY - blobCenterY;
          const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY);

          const maxDistance = 500;
          const maxRepel = 200;
          let repelX = 0, repelY = 0;

          if (distance < maxDistance && distance > 0) {
            const angle = Math.atan2(deltaY, deltaX);
            const force = (maxDistance - distance) / maxDistance;
            repelX = -Math.cos(angle) * force * maxRepel;
            repelY = -Math.sin(angle) * force * maxRepel;
          }

          const maxMovementX = 300;
          const finalX = Math.max(-maxMovementX, Math.min(maxMovementX, config.initialX + waveX + repelX));
          const finalY = config.initialY + waveY + repelY;

          blob.style.transform = `translate(${finalX}px, ${finalY}px) scale(${waveScale})`;

          const r1 = 50 + Math.sin(time * config.speed * 0.5) * 20;
          const r2 = 50 + Math.cos(time * config.speed * 0.7) * 20;
          const r3 = 50 + Math.sin(time * config.speed * 0.6) * 15;
          const r4 = 50 + Math.cos(time * config.speed * 0.8) * 25;

          blob.style.borderRadius = `${r1}% ${r2}% ${r3}% ${r4}% / ${r2}% ${r1}% ${r4}% ${r3}%`;
        });

        rafId = requestAnimationFrame(animate);
      }

      animate();

      window.addEventListener('beforeunload', function () {
        if (rafId) cancelAnimationFrame(rafId);
      });
    })();
  </script>
</section>
