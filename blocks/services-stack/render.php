<?php
/**
 * Render callback for Interactive Services Stack block
 */

$attributes = $attributes ?? [];
$title = $attributes['title'] ?? 'OUR SERVICES';
$subtitle = $attributes['subtitle'] ?? '';

$services = [
    [
        'title' => 'SEO ENGINE',
        'subtitle' => 'Fix foundations. Scale content. Win intent.',
        'description' => 'We repair architecture and speed, then publish intent-led content at scale to compound qualified traffic.',
        'tags' => ['Audits & CWV', 'Schema/IA', 'Internal Linking', 'Topic clusters & briefs'],
        'image' => '/wp-content/themes/vite-child/assets/images/seo-engine.png',

    ],
    [
        'title' => 'CREATIVE & MEDIA',
        'subtitle' => 'Creative that converts. Media that scales.',
        'description' => 'Modular static/motion assets tested weekly across Search & Social, optimized with profit guardrails and ROAS targets.',
          'tags' => ['Static/motion ads', 'UGC scripts', 'Account restructure', 'Weekly testing'],
          'image' => '/wp-content/themes/vite-child/assets/images/creative-media.png',
      ],
    [
        'title' => 'CRO & AUTOMATION',
        'subtitle' => 'Turn clicks into measurable revenue.',
        'description' => 'High-velocity landing pages and CRO experiments powered by clean, revenue-linked tracking and live dashboards.',
        'tags' => ['LP designs', 'A/B tests', 'Form/checkout optimization', 'Dashboards'],
        'image' => '/wp-content/themes/vite-child/assets/images/cro-automation.png',
    ],
];

$block_id = 'services-stack-' . wp_rand(1000, 9999);

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'services-stack-block alignwide',
    'id'    => $block_id,
]);

$title_words = preg_split('/\s+/', trim($title));
$word1 = $title_words[0] ?? '';
$word2 = implode(' ', array_slice($title_words, 1));
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="services-stack__cards"
         data-highlight-target="<?php echo esc_attr($block_id); ?>"
         style="--count: <?php echo esc_attr(count($services)); ?>;"
    >
        <div class="services-stack__stage">

            <!-- Header is INSIDE the sticky stage so it stays during stack,
                 then scrolls away when stage ends -->
            <div class="services-stack__header">
                <div class="services-stack__title-wrap">
                    <h2 class="services-stack__title">
                        <span class="services-stack__title-word services-stack__title-word--up"><?php echo esc_html($word1); ?></span>
                        <?php if ($word2) : ?>
                            <span class="services-stack__title-word services-stack__title-word--down"><?php echo esc_html($word2); ?></span>
                        <?php endif; ?>
                    </h2>

                    <?php if (!empty($subtitle)) : ?>
                        <p class="services-stack__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>

                <div class="services-stack__ornament" aria-hidden="true">
                    <i class="fa-solid fa-arrow-down-left"></i>
                </div>
            </div>

            <?php foreach ($services as $index => $service) : ?>
                <article
                    class="services-card <?php echo $index === 0 ? 'is-primary-card' : ''; ?>"
                    data-i="<?php echo esc_attr($index); ?>"
                >
                    <div class="services-card__inner">
                        <div class="services-card__top">
                            <p class="services-card__label">CARD <?php echo esc_html($index + 1); ?></p>
                            <h3 class="services-card__title"><?php echo esc_html($service['title']); ?></h3>
                            <p class="services-card__subtitle"> <?php echo esc_html($service['subtitle']); ?></p>
                            <p class="services-card__description"><?php echo esc_html($service['description']); ?></p>

                            <div class="services-card__tags">
                                <?php foreach ($service['tags'] as $tag) : ?>
                                    <span class="tag-pill"><?php echo esc_html($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="services-card__visual">
                          <?php if (!empty($service['image'])) : ?>
                            <img
                              class="services-card__image"
                              src="<?php echo esc_url($service['image']); ?>"
                              alt="<?php echo esc_attr($service['title']); ?>"
                              loading="lazy"
                              decoding="async"
                            />
                          <?php else : ?>
                            <div class="visual-placeholder" aria-hidden="true">
                              <span class="visual-dot"></span>
                              <span class="visual-dot"></span>
                              <span class="visual-dot"></span>
                            </div>
                          <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

        </div>

        <!-- scroll length for the animation -->
        <div class="services-stack__spacer" aria-hidden="true"></div>
    </div>
</section>

<script>
(function () {
  const blockId = <?php echo json_encode($block_id); ?>;
  const blockEl = document.getElementById(blockId);
  if (!blockEl) return;

  // Header title: switch to "stacked" (column) when it would overflow its container.
  const titleEl = blockEl.querySelector('.services-stack__title');
  let titleMeasureEl = null;

  function ensureTitleMeasureEl() {
    if (!titleEl || titleMeasureEl) return;
    titleMeasureEl = titleEl.cloneNode(true);
    titleMeasureEl.classList.remove('is-stacked');
    titleMeasureEl.setAttribute('aria-hidden', 'true');
    // Use fixed positioning so it can't affect page layout/scrollbars.
    titleMeasureEl.style.position = 'fixed';
    titleMeasureEl.style.left = '-9999px';
    titleMeasureEl.style.top = '-9999px';
    titleMeasureEl.style.visibility = 'hidden';
    titleMeasureEl.style.pointerEvents = 'none';
    titleMeasureEl.style.width = 'max-content';
    titleMeasureEl.style.maxWidth = 'none';
    titleMeasureEl.style.display = 'inline-flex';
    blockEl.appendChild(titleMeasureEl);
  }

  function fitTitleRow() {
    if (!titleEl) return;

    ensureTitleMeasureEl();
    if (!titleMeasureEl) return;

    // reset to CSS-driven size first
    titleEl.classList.remove('is-stacked');
    titleEl.style.fontSize = '';

    const maxSize = parseFloat(getComputedStyle(titleEl).fontSize) || 120;
    const minSize = 42; // <- adjust if you want it smaller before stacking

    // measure needed width at "max" size
    titleMeasureEl.style.fontSize = `${maxSize}px`;
    const needed = titleMeasureEl.getBoundingClientRect().width;
    const wrapEl = titleEl.closest('.services-stack__title-wrap') || titleEl.parentElement;
    const available = wrapEl.getBoundingClientRect().width;


    if (needed <= available + 1) return;

    // shrink proportionally
    const scale = available / needed;
    const newSize = Math.max(minSize, Math.floor(maxSize * scale));
    titleEl.style.fontSize = `${newSize}px`;

    // OPTIONAL safety: if even the min size can't fit, THEN allow stacking
    titleMeasureEl.style.fontSize = `${newSize}px`;
    if (titleMeasureEl.getBoundingClientRect().width > available + 1) {
        titleEl.classList.add('is-stacked');
        titleEl.style.fontSize = '';
    }
    }


  const wrap   = blockEl.querySelector('.services-stack__cards');
  const stage  = blockEl.querySelector('.services-stack__stage');
  const spacer = blockEl.querySelector('.services-stack__spacer');
  const cards  = Array.from(blockEl.querySelectorAll('.services-card'));
  const headerEl = blockEl.querySelector('.services-stack__header');
  let headerBaseTop = 0;
  const n = cards.length;

  function measureHeaderBaseTop() {
    if (!headerEl) return;
    // ensure we measure the unshifted position
    headerEl.style.setProperty('--header-shift', '0px');
    headerBaseTop = headerEl.getBoundingClientRect().top;
  }
  measureHeaderBaseTop();

  if (!wrap || !stage || !spacer || n < 2) return;

  const clamp = (v, a, b) => Math.min(Math.max(v, a), b);
  const smoothstep = (t) => t * t * (3 - 2 * t);

  function setCard(card, { y = 0, s = 1, blur = 0, opacity = 1, z = 1 }) {
    card.style.setProperty('--stack-y', `${y}px`);
    card.style.setProperty('--stack-s', s);
    card.style.setProperty('--stack-blur', `${blur}px`);
    card.style.setProperty('--stack-opacity', opacity);
    card.style.zIndex = String(z);
  }

  function update() {
    // The sticky stage starts when the wrapper hits the top of the page.
    const wrapRect = wrap.getBoundingClientRect();
    const startY = window.scrollY + wrapRect.top; // wrapper top in document coords
    const scrollLen = spacer.offsetHeight;
    const endY = startY + scrollLen;

    const pRaw = (window.scrollY - startY) / (endY - startY);
    const p = clamp(pRaw, 0, 1);

    // HOLD the last card stable near the end so it doesn't "jump up"
    const hold = 0.07; // last 7% is hold
    const pp = clamp(p / (1 - hold), 0, 1);

    const t = smoothstep(pp);

    const a = t * (n - 1);
    const i = Math.floor(a);
    const f = a - i;
    const ff = smoothstep(f);

    const cardH = cards[0]?.getBoundingClientRect().height || 720;

    // Next card starts from BELOW the viewport
    const enterY = (window.innerHeight * 0.62) + (cardH * 0.65);

    // Tuning
    const backStep    = 46;   // how much older cards shift DOWN behind
    const backScale   = 0.035;
    const blurStep    = 7;
    const minOpacity  = 0.18;

        // =========================
    // HEADER: go behind Card 1 before Card 2 appears
    // =========================
    if (headerEl) {
      // progress of the FIRST transition only (card 1 -> card 2)
      const firstPhase = clamp(a, 0, 1); // 0..1 only for the first step

      // Make it go behind early (before next card opacity starts at ff ~ 0.08)
      const behindT = smoothstep(clamp((firstPhase - 0.02) / 0.18, 0, 1)); // finishes ~0.20

      // Slide header downward into card 1 so it gets covered
      const moveT = smoothstep(clamp((firstPhase - 0.02) / 0.22, 0, 1));

      // Optional: fade out late in case edges still show outside the card
      const fadeT = smoothstep(clamp((firstPhase - 0.30) / 0.35, 0, 1));

      // If you want header initially in front, then behind:
      headerEl.style.zIndex = behindT > 0.01 ? '70' : '1500';

      // Compute how far to move header down so it sits inside card 1
      const cs = getComputedStyle(blockEl);
      const headerTopPx = headerEl.offsetTop; // resolved px (works even with clamp)
      const centerOffsetPx =
        parseFloat(cs.getPropertyValue('--services-card-center-offset')) || 0;


      // Card 1’s current stack-y during first transition (matches your card0 logic)
      const card0Y = (i === 0) ? (ff * backStep) : backStep;

      // Card top in stage/viewport coords (card is centered with translateY(-50% + offset + y))
      const cardTop =
        (window.innerHeight * 0.5) + centerOffsetPx + card0Y - (cardH * 0.5);

      // Put header a bit inside the card so it gets occluded
      const desiredHeaderTop = cardTop + 20;

      // shift down only (no negative)
      const shiftDown = Math.max(0, desiredHeaderTop - headerTopPx);

      headerEl.style.setProperty('--header-shift', `${moveT * shiftDown}px`);
      headerEl.style.setProperty('--header-opacity', `${1 - fadeT}`);
    }


    // 1) Default: ALL future cards hidden and parked below
    for (let k = 0; k < n; k++) {
      setCard(cards[k], { y: enterY, s: 1, blur: 0, opacity: 0, z: 1 });
    }

    // 2) Previous cards behind (down + blur)
    for (let k = 0; k < i; k++) {
      const depth = (i - k) + ff;
      const y = depth * backStep; // DOWN behind
      const s = clamp(1 - depth * backScale, 0.86, 1);
      const blur = depth * blurStep;
      const opacity = clamp(1 - depth * 0.22, minOpacity, 1);
      setCard(cards[k], { y, s, blur, opacity, z: 300 - k });
    }

    // 3) Current card moves into the back as the next arrives
    if (i < n) {
      const y = ff * backStep; // DOWN into the back
      const s = clamp(1 - ff * backScale, 0.90, 1);
      const blur = ff * blurStep;
      const opacity = clamp(1 - ff * 0.22, 0.78, 1);
      setCard(cards[i], { y, s, blur, opacity, z: 800 });
    }

    // 4) Next card: comes from below and overlaps ON TOP
    if (i + 1 < n) {
      const y = enterY * (1 - ff);
      const s = clamp(0.98 + ff * 0.02, 0.96, 1);

      // delay opacity so it doesn't show too early
      const opacity = clamp((ff - 0.08) / 0.92, 0, 1);

      setCard(cards[i + 1], { y, s, blur: 0, opacity, z: 1200 });
    }
  }

  let ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      update();
      ticking = false;
    });
  }

  

  update();
  fitTitleRow();

  // Re-sync when fonts load (can change measured width)
  if (document.fonts && typeof document.fonts.ready?.then === 'function') {
    document.fonts.ready.then(fitTitleRow).catch(() => {});
  }

  // Re-sync if the title/container resizes for any reason
  if (window.ResizeObserver && titleEl) {
    const ro = new ResizeObserver(() => fitTitleRow());
    ro.observe(titleEl);
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', () => {
    measureHeaderBaseTop();
    onScroll();
    fitTitleRow();
  }, { passive: true });

})();
</script>
