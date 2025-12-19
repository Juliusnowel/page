<?php
/**
 * Render callback for Our Approach block
 */

$attributes = $attributes ?? [];
$title = $attributes['title'] ?? 'OUR APPROACH';

// Sample steps; can be wired to real content later.
$steps = [
    [
        'title_lines' => ['RESEARCH', 'WITH RIGOR'],
        'hover_text'  => 'We start by stress-testing the site: technical audits, crawl/indexation checks, CWV, schema, and IA. Then we map real demand—keywords, SERP intent, competitors—and identify a “content moat” where you can win sustainably, not just spike.',
    ],
    [
        'title_lines' => ['SHIP TESTS', 'WEEKLY'],
        'hover_text'  => 'We run short, focused sprints: SEO releases (fixes, internal links, structured data) alongside creative experiments (static + motion variants, copy angles, LP tweaks). Small bets, shipped fast, so we learn what moves the needle early.',
    ],
    [
        'title_lines' => ['SCALE WHAT', 'WORKS'],
        'hover_text'  => 'Proven winners get budget and attention. We tighten bidding and audiences, expand high-intent content, and protect margins with ROAS/CAC guardrails. Live dashboards keep the team aligned on what to duplicate and what to drop.',
    ],
    [
        'title_lines' => ['PROVE THE', 'IMPACT'],
        'hover_text'  => 'Clean, durable tracking underpins every decision: server-side events, clear attribution models, and MMM guidance. We report on pipeline, revenue, and payback—so growth isn’t a guess, it’s measurable and defensible.',
    ],
];

$block_id = 'our-approach-' . wp_rand(1000, 9999);
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'our-approach-block alignwide',
    'id'    => $block_id,
]);

$title_words = preg_split('/\s+/', trim($title));
$word1 = $title_words[0] ?? '';
$word2 = implode(' ', array_slice($title_words, 1));
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="our-approach__header">
        <div class="our-approach__title-wrap">
            <h2 class="our-approach__title">
                <span class="our-approach__title-word our-approach__title-word--up"><?php echo esc_html($word1); ?></span>
                <?php if ($word2) : ?>
                    <span class="our-approach__title-word our-approach__title-word--down"><?php echo esc_html($word2); ?></span>
                <?php endif; ?>
            </h2>
        </div>

        <div class="our-approach__ornament" aria-hidden="true">
            <i class="fa-solid fa-arrow-down-left"></i>
        </div>
    </div>

    <div class="our-approach__steps" role="list">
        <?php foreach ($steps as $index => $step) : ?>
            <?php
            $num = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) . '/';
            ?>

            <article class="approach-step" role="listitem" tabindex="0">
                <div class="approach-step__content">
                    <div class="approach-step__num" aria-hidden="true"><?php echo esc_html($num); ?></div>
                    <h3 class="approach-step__title">
                        <?php foreach (($step['title_lines'] ?? []) as $line) : ?>
                            <span class="approach-step__title-line"><?php echo esc_html($line); ?></span>
                        <?php endforeach; ?>
                    </h3>
                </div>
                <div class="approach-step__hover-content">
                    <p class="approach-step__hover-text"><?php echo esc_html($step['hover_text']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<script>
    (function () {
        const blockId = <?php echo json_encode($block_id); ?>;
        const blockEl = document.getElementById(blockId);
        if (!blockEl) return;

        const steps = Array.from(blockEl.querySelectorAll('.approach-step'));
        if (!steps.length) return;

        const mql = window.matchMedia('(max-width: 1200px), (hover: none), (pointer: coarse)');

        let rafId = 0;
        let enabled = false;

        function clearCentered() {
            steps.forEach((s) => s.classList.remove('is-centered'));
        }

        function updateCentered() {
            rafId = 0;
            if (!enabled) return;

            const viewportCenter = window.innerHeight / 2;
            let bestStep = null;
            let bestDist = Infinity;

            for (const step of steps) {
                const rect = step.getBoundingClientRect();
                const stepCenter = rect.top + rect.height / 2;
                const dist = Math.abs(stepCenter - viewportCenter);
                if (dist < bestDist) {
                    bestDist = dist;
                    bestStep = step;
                }
            }

            const maxDist = window.innerHeight * 0.22; // only activate when reasonably near center
            steps.forEach((s) => s.classList.toggle('is-centered', s === bestStep && bestDist <= maxDist));
        }

        function scheduleUpdate() {
            if (!enabled) return;
            if (rafId) return;
            rafId = window.requestAnimationFrame(updateCentered);
        }

        function enable() {
            if (enabled) return;
            enabled = true;
            updateCentered();
            window.addEventListener('scroll', scheduleUpdate, { passive: true });
            window.addEventListener('resize', scheduleUpdate);
        }

        function disable() {
            if (!enabled) return;
            enabled = false;
            window.removeEventListener('scroll', scheduleUpdate);
            window.removeEventListener('resize', scheduleUpdate);
            clearCentered();
        }

        function sync() {
            if (mql.matches) enable();
            else disable();
        }

        // Init + respond to viewport changes
        sync();
        if (typeof mql.addEventListener === 'function') {
            mql.addEventListener('change', sync);
        } else if (typeof mql.addListener === 'function') {
            mql.addListener(sync);
        }
    })();
</script>
<script>
(function () {
  const blockId = <?php echo json_encode($block_id); ?>;
  const blockEl = document.getElementById(blockId);
  if (!blockEl) return;

  const titleEl = blockEl.querySelector('.our-approach__title');
  if (!titleEl) return;

  let measureEl = null;

  function ensureMeasureEl() {
    if (measureEl) return;
    measureEl = titleEl.cloneNode(true);
    measureEl.classList.remove('is-stacked');
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
    const wrapEl = blockEl.querySelector('.our-approach__title-wrap') || titleEl.parentElement;
    const available = wrapEl.getBoundingClientRect().width;

    if (needed <= available + 1) return;

    const scale = available / needed;
    const newSize = Math.max(minSize, Math.floor(maxSize * scale));
    titleEl.style.fontSize = `${newSize}px`;

    // if still too wide at min size, allow stacking as last resort
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

