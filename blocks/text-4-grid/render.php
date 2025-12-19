<?php
/**
 * Why Vite block render
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'c-why-vite',
	)
);
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="c-why-vite__inner">
		<div class="c-why-vite__intro">
			<h2 class="c-why-vite__eyebrow">Why Vite?</h2>
			<h3 class="c-why-vite__title">At Vite, we&rsquo;re not just building websites; 
				we&rsquo;re crafting digital ecosystems. We combine data-driven strategy with world-class design to create work that matters.</h3>
			<p class="c-why-vite__copy"> Join a team that challenges the norm daily.</p>
			<a class="c-why-vite__link" href="#contact">Learn more <span aria-hidden="true">→</span></a>
		</div>
		<div class="c-why-vite__grid" role="list">
			<article class="c-why-vite__card card_1" role="listitem">
				<div class="c-why-vite__icon" aria-hidden="true"></div>
				<h3 class="c-why-vite__card-title"><b>Impact Driven</b></h3>
				<p class="c-why-vite__card-copy">Your work lands in front of millions. We don&rsquo;t do busy work; we solve real problems.</p>
			</article>
			<article class="c-why-vite__card" role="listitem">
				<div class="c-why-vite__icon" aria-hidden="true"></div>
				<h3 class="c-why-vite__card-title"><b>Remote First</b></h3>
				<p class="c-why-vite__card-copy">Work from anywhere. We believe in output over hours. Your location is your choice.</p>
			</article>
			<article class="c-why-vite__card card_3" role="listitem">
				<div class="c-why-vite__icon" aria-hidden="true"></div>
				<h3 class="c-why-vite__card-title"><b>Holistic Health</b></h3>
				<p class="c-why-vite__card-copy">Health coverage for you and your dependents, plus mental health days.</p>
			</article>
			<article class="c-why-vite__card" role="listitem">
				<div class="c-why-vite__icon" aria-hidden="true"></div>
				<h3 class="c-why-vite__card-title"><b>Continuous Growth</b></h3>
				<p class="c-why-vite__card-copy">Learning stipends, conference budgets, and mentorship from leaders.</p>
			</article>
		</div>
	</div>
</section>
