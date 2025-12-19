<?php
if ( ! defined('ABSPATH') ) { exit; }

/**
 * vite-child/image-cards-slider
 * attrs:
 * - title (string)
 * - text (string)
 * - cards (array of { url, alt })
 */

$title = trim((string)($attributes['title'] ?? ''));
$text  = trim((string)($attributes['text'] ?? ''));

$cards_raw = $attributes['cards'] ?? [];
$cards_raw = is_array($cards_raw) ? $cards_raw : [];

/** sanitize cards */
$cards = [];
foreach ($cards_raw as $c) {
  if (!is_array($c)) continue;
  $url = trim((string)($c['url'] ?? ''));
  if ($url === '') continue;
  $alt = trim((string)($c['alt'] ?? ''));
  $cards[] = ['url' => $url, 'alt' => $alt];
}

/** if empty, show placeholders so layout is visible */
$placeholder_count = 4;

$wrapper_attributes = get_block_wrapper_attributes([
  'class' => 'img-cards-slider',
]);

?>
<section <?= $wrapper_attributes; ?>>
  <div class="img-cards-slider__top">
    <?php if ($title !== ''): ?>
      <h2 class="img-cards-slider__title"><?= esc_html($title); ?></h2>
    <?php endif; ?>

    <?php if ($text !== ''): ?>
      <p class="img-cards-slider__text"><?= esc_html($text); ?></p>
    <?php endif; ?>
  </div>

  <div class="img-cards-slider__viewport" aria-label="Image cards slider">
    <div class="img-cards-slider__track" role="list">
      <?php if (!empty($cards)): ?>
        <?php foreach ($cards as $card): ?>
          <div class="img-cards-slider__card" role="listitem">
          <img
            class="img-cards-slider__img"
            src="<?= esc_url($card['url']); ?>"
            alt="<?= esc_attr($card['alt']); ?>"
            loading="lazy"
            decoding="async"
            draggable="false"
          />

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php for ($i = 0; $i < $placeholder_count; $i++): ?>
          <div class="img-cards-slider__card img-cards-slider__card--placeholder" aria-hidden="true"></div>
        <?php endfor; ?>
      <?php endif; ?>
    </div>
  </div>

  <script>
(function(){
  var viewports = document.querySelectorAll('.wp-block-vite-child-image-cards-slider .img-cards-slider__viewport:not(.js-bound)');
  if (!viewports.length) return;

  viewports.forEach(function(viewport){
    viewport.classList.add('js-bound');

    var track = viewport.querySelector('.img-cards-slider__track');
    if (!track) return;

    // prevent any native drag behavior
    viewport.addEventListener('dragstart', function(e){ e.preventDefault(); });

    // ---- LOOP SETUP (duplicate once) ----
    var originals = Array.prototype.slice.call(track.children);
    var canLoop = originals.length >= 4;
    var setWidth = 0;

    function measureSetWidth(){
      setWidth = canLoop ? (track.scrollWidth / 2) : 0;
      viewport.__loopSetWidth = setWidth;
    }

    function keepLoop(){
      if (!viewport.__loopSetWidth) return;

      var w = viewport.__loopSetWidth;

      // real max scrollLeft for 2 sets
      var max = (w * 2) - viewport.clientWidth;

      // wrap BEFORE hitting the hard ends
      var buffer = Math.max(48, viewport.clientWidth * 0.12);

      var x = viewport.scrollLeft;

      // left end -> jump forward one set
      if (x < buffer) viewport.scrollLeft = x + w;

      // right end -> jump backward one set
      else if (x > (max - buffer)) viewport.scrollLeft = x - w;
    }

    // bind scroll loop
    viewport.addEventListener('scroll', keepLoop, { passive: true });

    // duplicate items once (for infinity)
    if (canLoop && !track.classList.contains('is-looped')){
      originals.forEach(function(node){
        track.appendChild(node.cloneNode(true));
      });
      track.classList.add('is-looped');
    }

    // initial measure + start position
    requestAnimationFrame(function(){
      measureSetWidth();
      if (viewport.__loopSetWidth) {
        viewport.scrollLeft = viewport.__loopSetWidth;
        keepLoop();
      }
    });

    // resize: re-measure + keep in safe zone
    window.addEventListener('resize', function(){
      requestAnimationFrame(function(){
        measureSetWidth();
        keepLoop();
      });
    });

    // ---- DRAG + MOMENTUM ----
    var isDown = false;
    var startX = 0;
    var startScroll = 0;

    var lastX = 0;
    var lastT = 0;
    var vel = 0;     // px per ms
    var raf = 0;

    function stopMomentum(){
      if (raf) cancelAnimationFrame(raf);
      raf = 0;
    }

    viewport.addEventListener('pointerdown', function(e){
      if (e.button !== undefined && e.button !== 0) return;
      isDown = true;

      viewport.setPointerCapture(e.pointerId);
      viewport.classList.add('is-dragging');
      stopMomentum();

      startX = e.clientX;
      startScroll = viewport.scrollLeft;

      lastX = e.clientX;
      lastT = performance.now();
      vel = 0;

      e.preventDefault();
    });

    viewport.addEventListener('pointermove', function(e){
      if (!isDown) return;

      var x = e.clientX;
      var dx = x - startX;

      viewport.scrollLeft = startScroll - dx;

      var now = performance.now();
      var dt = (now - lastT) || 16;

      // velocity in scroll space (positive = scroll right)
      vel = (lastX - x) / dt;

      lastX = x;
      lastT = now;

      keepLoop();
      e.preventDefault();
    });

    function endDrag(){
      if (!isDown) return;
      isDown = false;
      viewport.classList.remove('is-dragging');

      // boost so fast swipe = fast glide
      vel *= 1.35;

      var friction = 0.95;
      var minVel = 0.02;

      function step(){
        keepLoop();
        viewport.scrollLeft += vel * 16;
        vel *= friction;

        if (Math.abs(vel) > minVel){
          raf = requestAnimationFrame(step);
        } else {
          raf = 0;
        }
      }

      if (Math.abs(vel) > 0.04){
        raf = requestAnimationFrame(step);
      }
    }

    viewport.addEventListener('pointerup', endDrag);
    viewport.addEventListener('pointercancel', endDrag);
    viewport.addEventListener('pointerleave', endDrag);
  });
})();
</script>



</section>
