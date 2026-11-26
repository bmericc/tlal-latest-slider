<?php
/**
 * Plugin Name: TLAL Latest Posts Slider
 * Description: Lightweight single-slide (fade) slider of the latest blog posts, in the style of the Travelify featured slider. Shows automatically below the header on the homepage (GeneratePress) and via the [tlal_slider] shortcode. No external libraries.
 * Version: 2.0.1
 * Author: Bahri Meriç Canlı
 * License: GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

const TLAL_SLIDER_VERSION = '2.0.1';

/**
 * Render the slider markup.
 *
 * @param array $atts count, category (slug), autoplay (ms, 0 = off),
 *                    order (desc = newest first, asc = the latest N shown oldest to newest).
 */
function tlal_slider_render( $atts = [] ) {
	$a = shortcode_atts(
		[
			'count'    => 5,
			'category' => '',
			'autoplay' => 5000,
			'order'    => 'desc',
		],
		$atts,
		'tlal_slider'
	);

	$q = new WP_Query(
		[
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, min( 20, (int) $a['count'] ) ),
			'category_name'       => sanitize_title( $a['category'] ),
			'meta_key'            => '_thumbnail_id', // only posts with a featured image.
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		]
	);

	if ( ! $q->have_posts() ) {
		return '';
	}

	// Always pick the latest N posts; 'asc' only reverses their display order.
	if ( 'asc' === strtolower( $a['order'] ) ) {
		$q->posts = array_reverse( $q->posts );
	}

	wp_enqueue_style( 'tlal-slider' );
	wp_enqueue_script( 'tlal-slider' );

	$total = count( $q->posts );
	$i     = 0;
	ob_start();
	?>
	<section class="tlal-slider" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Latest posts', 'tlal-slider' ); ?>" data-autoplay="<?php echo (int) $a['autoplay']; ?>">
		<div class="tlal-slider__slides">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$active  = 0 === $i;
				$excerpt = wp_trim_words( get_the_excerpt(), 28 );
				?>
				<div class="tlal-slide<?php echo $active ? ' is-active' : ''; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( '%d / %d', $i + 1, $total ) ); ?>"<?php echo $active ? '' : ' aria-hidden="true" inert'; ?>>
					<a class="tlal-slide__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php
						the_post_thumbnail(
							'full',
							[
								'alt'           => the_title_attribute( [ 'echo' => false ] ),
								'loading'       => $active ? 'eager' : 'lazy',
								'fetchpriority' => $active ? 'high' : 'auto',
								'sizes'         => '(max-width: 1200px) 100vw, 1200px',
							]
						);
						?>
					</a>
					<div class="tlal-slide__text">
						<h2 class="tlal-slide__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php if ( $excerpt ) : ?>
							<p class="tlal-slide__excerpt"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
		<?php if ( $total > 1 ) : ?>
			<button type="button" class="tlal-slider__nav tlal-slider__nav--prev" aria-label="<?php esc_attr_e( 'Previous', 'tlal-slider' ); ?>">&#10094;</button>
			<button type="button" class="tlal-slider__nav tlal-slider__nav--next" aria-label="<?php esc_attr_e( 'Next', 'tlal-slider' ); ?>">&#10095;</button>
			<div class="tlal-slider__dots">
				<?php for ( $d = 0; $d < $total; $d++ ) : ?>
					<button type="button" class="tlal-slider__dot<?php echo 0 === $d ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'tlal-slider' ), $d + 1 ) ); ?>"<?php echo 0 === $d ? ' aria-current="true"' : ''; ?>></button>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}

add_shortcode( 'tlal_slider', 'tlal_slider_render' );

add_action(
	'wp_enqueue_scripts',
	function () {
		$base = plugin_dir_url( __FILE__ );
		wp_register_style( 'tlal-slider', $base . 'slider.css', [], TLAL_SLIDER_VERSION );
		wp_register_script( 'tlal-slider', $base . 'slider.js', [], TLAL_SLIDER_VERSION, [ 'in_footer' => true, 'strategy' => 'defer' ] );
	}
);

/**
 * Auto-insert on the homepage right below <header> (GeneratePress hook).
 * Skipped on AMP requests and on paginated pages (/page/2/ ...).
 */
function tlal_slider_home_output() {
	if ( ! ( is_front_page() || is_home() ) || is_paged() ) {
		return;
	}
	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		return;
	}
	$atts = apply_filters( 'tlal_slider_home_atts', [ 'count' => 5, 'order' => 'asc' ] );
	echo '<div class="tlal-slider-wrap grid-container">' . tlal_slider_render( $atts ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
}
add_action( 'generate_after_header', 'tlal_slider_home_output' );
