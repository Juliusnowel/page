<?php
/**
 * Render callback for Our Works Portfolio block
 */

$attributes = $attributes ?? [];
$title = $attributes['title'] ?? 'OUR WORKS';

$theme_uri = get_stylesheet_directory_uri();

$works = [
    [
      'title' => 'CLEARSIGNAL ANALYTICS',
      'icon'  => 'chart-line',
      'image' => $theme_uri . '/assets/images/clear-signal-analytics.png',
      'url'   => '#',
    ],
    [
      'title' => 'NIMBUS FINANCE',
      'icon'  => 'wallet',
      'image' => $theme_uri . '/assets/images/nimbus-finance.png',
      'url'   => '#',
    ],
    [
      'title' => 'LUMA HEALTH',
      'icon'  => 'heart-pulse',
      'image' => $theme_uri . '/assets/images/luma-health.png',
      'url'   => '#',
    ],
    [
      'title' => 'RELAY COMMERCE',
      'icon'  => 'shopping-bag',
      'image' => $theme_uri . '/assets/images/relay-commerce.png',
      'url'   => '#',
    ],
    [
      'title' => 'ATLAS AI',
      'icon'  => 'robot',
      'image' => $theme_uri . '/assets/images/atlas-ai.png',
      'url'   => '#',
    ],
];  

$block_id = 'our-works-' . wp_rand(1000, 9999);
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'our-works-block alignwide',
    'id'    => $block_id,
]);

// Split title into words for offset effect
$title_words = explode(' ', $title);
$first_word = $title_words[0] ?? '';
$second_word = $title_words[1] ?? '';
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="our-works__header">
        <div class="our-works__title-wrapper">
            <h2 class="our-works__title">
                <span class="title-word title-word--up"><?php echo esc_html($first_word); ?></span>
                <?php if ($second_word) : ?>
                    <span class="title-word title-word--down"><?php echo esc_html($second_word); ?></span>
                <?php endif; ?>
            </h2>
        </div>
        <!-- <div class="our-works__icon" aria-hidden="true">
            <i class="fa-solid fa-arrow-down-left"></i>
        </div> -->
    </div>

    <div class="our-works__grid">
        <?php foreach ($works as $index => $work) : 
            $size_class = ($index < 2) ? 'work-card--lg' : 'work-card--sm';
        ?>
            <article class="work-card <?php echo esc_attr($size_class); ?>" data-work-index="<?php echo esc_attr($index); ?>">
                <a class="work-card__link" href="<?php echo esc_url($work['url'] ?? '#'); ?>">
                    <div class="work-card__inner">
                    <header class="work-card__header">
                        <h3 class="work-card__title"><?php echo esc_html($work['title']); ?></h3>

                        <span class="work-card__arrow" aria-hidden="true">
                        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/Vector.svg' ); ?>" alt="" loading="lazy" decoding="async" />
                        </span>
                    </header>

                    <div class="work-card__media">
                        <?php if (!empty($work['image'])) : ?>
                        <img src="<?php echo esc_url($work['image']); ?>" alt="<?php echo esc_attr($work['title']); ?>" loading="lazy" decoding="async" />
                        <?php else : ?>
                        <div class="work-card__placeholder">
                            <span class="placeholder-text"><?php echo esc_html($work['title']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    </div>
                </a>
            </article>

        <?php endforeach; ?>
    </div>
</section>

<script>
(function () {
  const blockId = <?php echo json_encode($block_id); ?>;
  const blockEl = document.getElementById(blockId);
  if (!blockEl) return;

  const titleEl = blockEl.querySelector('.our-works__title');
  if (!titleEl) return;

  let measureEl = null;

  function ensureMeasureEl() {
    if (measureEl) return;
    measureEl = titleEl.cloneNode(true);
    measureEl.setAttribute('aria-hidden', 'true');
    measureEl.style.position = 'fixed';
    measureEl.style.left = '-9999px';
    measureEl.style.top = '-9999px';
    measureEl.style.visibility = 'hidden';
    measureEl.style.pointerEvents = 'none';
    measureEl.style.width = 'max-content';
    measureEl.style.maxWidth = 'none';
    measureEl.style.display = 'inline-flex';
    blockEl.appendChild(measureEl);
  }

  function fitTitleRow() {
    ensureMeasureEl();

    // reset first
    titleEl.classList.remove('is-stacked');
    titleEl.style.fontSize = '';

    const maxSize = parseFloat(getComputedStyle(titleEl).fontSize) || 120;
    const minSize = 42;

    measureEl.style.fontSize = `${maxSize}px`;

    const needed = measureEl.getBoundingClientRect().width;
    const wrapEl = blockEl.querySelector('.our-works__title-wrapper') || titleEl.parentElement;
    const available = wrapEl.getBoundingClientRect().width;

    if (needed <= available + 1) return;

    const scale = available / needed;
    const newSize = Math.max(minSize, Math.floor(maxSize * scale));
    titleEl.style.fontSize = `${newSize}px`;

    // If still too big at min-size, allow stacking as a last resort
    measureEl.style.fontSize = `${newSize}px`;
    if (measureEl.getBoundingClientRect().width > available + 1) {
      titleEl.classList.add('is-stacked');
      titleEl.style.fontSize = '';
    }
  }

  fitTitleRow();

  if (document.fonts && typeof document.fonts.ready?.then === 'function') {
    document.fonts.ready.then(fitTitleRow).catch(() => {});
  }

  if (window.ResizeObserver) {
    const ro = new ResizeObserver(() => fitTitleRow());
    ro.observe(blockEl);
  }

  window.addEventListener('resize', fitTitleRow, { passive: true });
})();
</script>
