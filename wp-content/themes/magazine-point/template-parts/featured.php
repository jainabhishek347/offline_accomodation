<?php

$featured_news_category = magazine_point_get_option( 'featured_news_category' );

$qargs = array(
	'posts_per_page'      => 5,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
);

if ( absint( $featured_news_category ) > 0 ) {
	$qargs['cat'] = absint( $featured_news_category );
}

$the_query = new WP_Query( $qargs );
?>

<?php if ( $the_query->have_posts() ) : ?>

	<div id="featured-news">
			<div class="container">
				<div class="inner-wrapper">
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

						<?php $special_class = ( 0 === $the_query->current_post ) ? 'main-featured-news-item' : ''; ?>
						<div class="featured-news-item <?php echo esc_attr( $special_class ); ?>">
							<div class="featured-news-wrapper">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="featured-news-thumb">
										<a href="<?php the_permalink(); ?>">
											<?php
											$img_attributes = array( 'class' => 'aligncenter' );
											the_post_thumbnail( 'magazine-point-featured', $img_attributes );
											?>
										</a>
									</div>
								<?php else : ?>
									<div class="featured-news-thumb">
										<img src="<?php echo esc_url ( get_template_directory_uri().'/images/no-image.png' ); ?>" alt="" />
									</div>
								<?php endif; ?>

								<div class="featured-news-text-content">
									<?php $cat_detail = magazine_point_get_single_post_category( get_the_ID() ); ?>
									<?php if ( ! empty( $cat_detail ) ) : ?>
										<span class="featured-post-category"> <a href="<?php echo esc_url( $cat_detail['url'] ); ?>"><?php echo esc_html( $cat_detail['name'] ); ?></a></span>
									<?php endif; ?>

									<h3 class="featured-news-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<?php if ( 0 === $the_query->current_post ) : ?>
										<div class="featured-news-meta entry-meta">
											<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
											<?php
											if ( comments_open( get_the_ID() ) ) {
												echo '<span class="comments-link">';
												comments_popup_link( '0', '1', '%' );
												echo '</span>';
											}
											?>
										</div> <!-- .featured-news-meta -->
									<?php endif; ?>
								</div> <!-- .featured-news-text-content -->
							</div> <!-- .featured-news-wrapper -->
						</div> <!-- .featured-news-item  -->

					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

			</div> <!-- .inner-wrapper -->
		</div> <!-- .container -->
	</div> <!-- #featured-news -->

<?php endif;
