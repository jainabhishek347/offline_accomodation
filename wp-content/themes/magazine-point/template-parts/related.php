<?php
/**
 * Related posts.
 *
 * @package Magazine_Point
 */

$qargs = array(
	'posts_per_page'      => 3,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
);

$current_object = get_queried_object();

if ( $current_object instanceof WP_Post ) {
	$current_id = $current_object->ID;

	if ( absint( $current_id ) > 0 ) {
		// Exclude current post.
		$qargs['post__not_in'] = array( absint( $current_id ) );

		// Include current posts categories.
		$categories_detail = wp_get_post_categories( $current_id );
		if ( ! empty( $categories_detail ) ) {
			$qargs['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $categories_detail,
					'operator' => 'IN',
					)
				);
		}
	}
}

$the_query = new WP_Query( $qargs );
?>

<?php if ( $the_query->have_posts() ) : ?>
	<div class="related-posts-wrapper related-posts-column-3">

		<h4><?php esc_html_e( 'Related Posts', 'magazine-point' ); ?></h4>

		<div class="inner-wrapper">

			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

				<div class="related-posts-item">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="related-posts-thumb">
							<a href="<?php the_permalink(); ?>">
								<?php
								$img_attributes = array( 'class' => 'aligncenter' );
								the_post_thumbnail( 'medium', $img_attributes );
								?>
							</a>
						</div>
					<?php else : ?>
						<div class="related-posts-thumb">
							<img src="<?php echo esc_url ( get_template_directory_uri().'/images/no-image.png' ); ?>"/>
						</div>
					<?php endif; ?>

					<div class="related-posts-text-wrap">
						<div class="related-posts-meta entry-meta">
							<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
							<?php
							if ( comments_open( get_the_ID() ) ) {
								echo '<span class="comments-link">';
								comments_popup_link( '0', '1', '%' );
								echo '</span>';
							}
							?>
						</div><!-- .related-posts-meta -->
						<h3 class="related-posts-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
					</div><!-- .related-posts-text-wrap -->

				</div><!-- .related-posts-item -->

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		</div><!-- .inner-wrapper -->
	</div><!-- .related-posts-wrapper -->

<?php endif;
