<?php
/**
 * Custom theme widgets.
 *
 * @package Magazine_Point
 */

// Load widget helper.
require_once get_template_directory() . '/vendors/widget-helper/widget-helper.php';

if ( ! function_exists( 'magazine_point_register_widgets' ) ) :

	/**
	 * Register widgets.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_register_widgets() {

		// Social widget.
		register_widget( 'Magazine_Point_Social_Widget' );

		// Recent Posts extended widget.
		register_widget( 'Magazine_Point_Recent_Posts_Extended_Widget' );

		// News block widget.
		register_widget( 'Magazine_Point_News_Block_Widget' );

		// Posts Slider widget.
		register_widget( 'Magazine_Point_Posts_Slider_Widget' );

		// Featured Page widget.
		register_widget( 'Magazine_Point_Featured_Page_Widget' );

		// Tabbed widget.
		register_widget( 'Magazine_Point_Tabbed_Widget' );
	}

endif;

add_action( 'widgets_init', 'magazine_point_register_widgets' );

if ( ! class_exists( 'Magazine_Point_Social_Widget' ) ) :

	/**
	 * Social widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_Social_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-social';
			$args['label'] = esc_html__( 'MP: Social', 'magazine-point' );

			$args['widget'] = array(
				'classname'                   => 'magazine_point_widget_social',
				'description'                 => esc_html__( 'Social Icons Widget', 'magazine-point' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'magazine-point' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			if ( has_nav_menu( 'social' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'social',
					'container'      => false,
					'depth'          => 1,
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
				) );
			}

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Magazine_Point_Recent_Posts_Extended_Widget' ) ) :

	/**
	 * Recent posts extended widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_Recent_Posts_Extended_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-recent-posts-extended';
			$args['label'] = esc_html__( 'MP: Recent Posts Extended', 'magazine-point' );

			$args['widget'] = array(
				'classname'                   => 'magazine_point_widget_recent_posts_extended',
				'description'                 => esc_html__( 'Recent posts extended widget', 'magazine-point' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'magazine-point' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'post_category' => array(
					'label'           => esc_html__( 'Select Category:', 'magazine-point' ),
					'type'            => 'dropdown-taxonomies',
					'show_option_all' => esc_html__( 'All Categories', 'magazine-point' ),
					),
				'post_number' => array(
					'label'   => esc_html__( 'Number of Posts:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 5,
					'min'     => 1,
					'max'     => 100,
					),
				'image_width' => array(
					'label'       => esc_html__( 'Image Width:', 'magazine-point' ),
					'type'        => 'number',
					'description' => esc_html__( 'px', 'magazine-point' ),
					'default'     => 90,
					'min'         => 1,
					'max'         => 200,
					),
				'disable_thumbnail' => array(
					'label'   => esc_html__( 'Disable Thumbnail', 'magazine-point' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				'disable_date' => array(
					'label'   => esc_html__( 'Disable Date', 'magazine-point' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			?>
			<?php
			$qargs = array(
				'posts_per_page'      => absint( $values['post_number'] ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				);

			if ( absint( $values['post_category'] ) > 0 ) {
				$qargs['cat'] = absint( $values['post_category'] );
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="recent-posts-extended-widget">

					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<div class="recent-posts-extended-item">

							<?php if ( false === $values['disable_thumbnail'] && has_post_thumbnail() ) : ?>
								<div class="recent-posts-extended-thumb">
									<a href="<?php the_permalink(); ?>">
										<?php
										$img_attributes = array(
											'class' => 'alignleft',
											'style' => 'max-width:' . absint( $values['image_width'] ) . 'px;',
											);
										the_post_thumbnail( 'thumbnail', $img_attributes );
										?>
									</a>
								</div>
							<?php endif; ?>
							<div class="recent-posts-extended-text-wrap">
								<h3 class="recent-posts-extended-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>

								<?php if ( false === $values['disable_date'] ) : ?>
									<div class="recent-posts-extended-meta entry-meta">
										<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
									</div>
								<?php endif; ?>

							</div><!-- .recent-posts-extended-text-wrap -->

						</div><!-- .recent-posts-extended-item -->
					<?php endwhile; ?>

				</div><!-- .recent-posts-extended-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

			<?php

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Magazine_Point_News_Block_Widget' ) ) :

	/**
	 * News block widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_News_Block_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-news-block';
			$args['label'] = esc_html__( 'MP: News Block', 'magazine-point' );

			$args['widget'] = array(
				'classname'                   => 'magazine_point_widget_news_block',
				'description'                 => esc_html__( 'News block Widget', 'magazine-point' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'magazine-point' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'news_category' => array(
					'label'           => esc_html__( 'Select Category:', 'magazine-point' ),
					'type'            => 'dropdown-taxonomies',
					'show_option_all' => esc_html__( 'All Categories', 'magazine-point' ),
					),
				'news_number' => array(
					'label'   => esc_html__( 'Number of Posts:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 3,
					'min'     => 1,
					'max'     => 8,
					),
				'news_column' => array(
					'label'   => esc_html__( 'Select Column:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 3,
					'choices' => array( '1' => 1,'2' => 2, '3' => 3, '4' => 4 ),
					),
				'news_image' => array(
					'label'   => esc_html__( 'Select Image:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 'magazine-point-thumb',
					'choices' => magazine_point_get_image_sizes_options( false ),
					),
				'excerpt_length' => array(
					'label'   => esc_html__( 'Excerpt Length:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 12,
					'min'     => 0,
					'max'     => 100,
					),
				'news_layout' => array(
					'label'   => esc_html__( 'Select News Layout:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 2,
					'choices' => array( '1' => 1, '2' => 2 ),
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			$qargs = array(
				'posts_per_page'      => esc_attr( $values['news_number'] ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			if ( absint( $values['news_category'] ) > 0 ) {
				$qargs['cat'] = absint( $values['news_category'] );
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="news-block-widget news-block-layout-<?php echo esc_attr( $values['news_layout'] ); ?> news-block-column-<?php echo esc_attr( $values['news_column'] ); ?>">

					<div class="inner-wrapper">

						<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<div class="news-block-item">
								<div class="news-block-wrapper">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="news-block-thumb">
											<a href="<?php the_permalink(); ?>">
												<?php
												$img_attributes = array( 'class' => 'aligncenter' );
												the_post_thumbnail( esc_attr( $values['news_image'] ), $img_attributes );
												?>
											</a>
											<?php $cat_detail = magazine_point_get_single_post_category( get_the_ID() ); ?>
											<?php if ( ! empty( $cat_detail ) ) : ?>
												<span class="news-categories"> <a href="<?php echo esc_url( $cat_detail['url'] ); ?>"><?php echo esc_html( $cat_detail['name'] ); ?></a></span>
											<?php endif; ?>
										</div><!-- .news-block-thumb -->

									<?php endif; ?>
									<div class="news-block-text-wrap">
										<div class="news-block-meta entry-meta">
											<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
											<?php
											if ( comments_open( get_the_ID() ) ) {
												echo '<span class="comments-link">';
												comments_popup_link( '0', '1', '%' );
												echo '</span>';
											}
											?>
										</div><!-- .news-block-meta -->
										<h3 class="news-block-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>

										<?php if ( absint( $values['excerpt_length'] ) > 0 ) : ?>
											<div class="news-block-summary">
												<?php
												$excerpt = magazine_point_get_the_excerpt( absint( $values['excerpt_length'] ) );
												echo wp_kses_post( wpautop( $excerpt ) );
												?>
											</div><!-- .news-block-summary -->
										<?php endif; ?>

									</div><!-- .news-block-text-wrap -->
								</div><!-- .news-block-wrapper -->
							</div><!-- .news-block-item -->

						<?php endwhile; ?>

					</div><!-- .inner-wrapper -->
				</div><!-- .news-block-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>
			<?php

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Magazine_Point_Posts_Slider_Widget' ) ) :

	/**
	 * Posts slider widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_Posts_Slider_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-posts-slider';
			$args['label'] = esc_html__( 'MP: Posts Slider', 'magazine-point' );

			$args['widget'] = array(
				'classname'                   => 'magazine_point_widget_posts_slider',
				'description'                 => esc_html__( 'Posts Slider Widget', 'magazine-point' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'magazine-point' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'post_category' => array(
					'label'           => esc_html__( 'Select Category:', 'magazine-point' ),
					'type'            => 'dropdown-taxonomies',
					'show_option_all' => esc_html__( 'All Categories', 'magazine-point' ),
					),
				'post_number' => array(
					'label'   => esc_html__( 'Number of Posts:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 4,
					'min'     => 2,
					'max'     => 10,
					),
				'featured_image' => array(
					'label'   => esc_html__( 'Select Image:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 'magazine-point-featured',
					'choices' => magazine_point_get_image_sizes_options( false ),
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $values['title'] ) ) {
				echo $args['before_title'] . esc_html( $values['title'] ) . $args['after_title'];
			}

			$qargs = array(
				'posts_per_page' => absint( $values['post_number'] ),
				'no_found_rows'  => true,
				'meta_key'       => '_thumbnail_id',
			);

			if ( absint( $values['post_category'] ) > 0 ) {
				$qargs['cat'] = absint( $values['post_category'] );
			}

			$the_query = new WP_Query( $qargs );

			if ( $the_query->have_posts() ) { ?>

				<div class="cycle-slideshow" data-cycle-slides="article" data-cycle-auto-height="container" data-pager-template='<span class="pager-box"></span>'>

					<div class="cycle-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
					<div class="cycle-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>

					<?php $count = 1; ?>
					<?php while ( $the_query->have_posts() ) { ?>
						<?php $the_query->the_post(); ?>

						<?php $class_text = ( 1 === $count ) ? 'first' : ''; ?>

						<article class="<?php echo esc_attr( $class_text ); ?>">
							<?php the_post_thumbnail( esc_attr( $values['featured_image'] ) ); ?>
							<div class="slide-caption">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							</div><!-- .slide-caption -->
						</article>

						<?php $count++; ?>

					<?php } // End while.

					// Reset
					wp_reset_postdata(); ?>

					<div class="cycle-pager"></div>

				</div><!-- .cycle-slideshow -->
				<?php

			} // End if have posts.

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Magazine_Point_Featured_Page_Widget' ) ) :

	/**
	 * Featured page widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_Featured_Page_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-featured-page';
			$args['label'] = esc_html__( 'MP: Featured Page', 'magazine-point' );

			$args['widget'] = array(
				'classname'                   => 'magazine_point_widget_featured_page',
				'description'                 => esc_html__( 'Displays single featured Page', 'magazine-point' ),
				'customize_selective_refresh' => true,
			);

			$args['fields'] = array(
				'title' => array(
					'label' => esc_html__( 'Title:', 'magazine-point' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'featured_page' => array(
					'label'            => esc_html__( 'Select Page:', 'magazine-point' ),
					'type'             => 'dropdown-pages',
					'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'magazine-point' ),

					),
				'content_type' => array(
					'label'   => esc_html__( 'Show Content:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 'full',
					'choices' => array(
						'short' => esc_html__( 'Short', 'magazine-point' ),
						'full'  => esc_html__( 'Full', 'magazine-point' ),
						),
					),
				'excerpt_length' => array(
					'label'       => esc_html__( 'Excerpt Length:', 'magazine-point' ),
					'description' => esc_html__( 'Applies when Short is selected in Show Content.', 'magazine-point' ),
					'type'        => 'number',
					'default'     => 40,
					'min'         => 1,
					'max'         => 100,
					),
				'featured_image' => array(
					'label'   => esc_html__( 'Select Image:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 'medium',
					'choices' => magazine_point_get_image_sizes_options(),
					),
				'featured_image_alignment' => array(
					'label'   => esc_html__( 'Select Image Alignment:', 'magazine-point' ),
					'type'    => 'select',
					'default' => 'center',
					'choices' => magazine_point_get_image_alignment_options(),
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$values['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( absint( $values['featured_page'] ) > 0 ) {

				$qargs = array(
					'p'             => absint( $values['featured_page'] ),
					'post_type'     => 'page',
					'no_found_rows' => true,
					);

				$the_query = new WP_Query( $qargs );

				if ( $the_query->have_posts() ) {

					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						// Display featured image.
						if ( 'disable' !== $values['featured_image'] && has_post_thumbnail() ) {
							the_post_thumbnail( esc_attr( $values['featured_image'] ), array( 'class' => 'align' . esc_attr( $values['featured_image_alignment'] ) ) );
						}

						echo '<div class="featured-page-widget">';

						// Render widget title.
						if ( ! empty( $values['title'] ) ) {
							echo $args['before_title'] . $values['title'] . $args['after_title'];
						}

						if ( 'short' === $values['content_type'] ) {
							if ( absint( $values['excerpt_length'] ) > 0 ) {
								$excerpt = magazine_point_get_the_excerpt( absint( $values['excerpt_length'] ) );
								echo wp_kses_post( wpautop( $excerpt ) );
								echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__( 'Read more', 'magazine-point' ) . '</a>';
							}
						} else {
							the_content();
						}

						echo '</div><!-- .featured-page-widget -->';

					} // End while.

					// Reset.
					wp_reset_postdata();

				} // End if.
			}

			echo $args['after_widget'];

		}

	}

endif;

if ( ! class_exists( 'Magazine_Point_Tabbed_Widget' ) ) :

	/**
	 * Tabbed widget class.
	 *
	 * @since 1.0.0
	 */
	class Magazine_Point_Tabbed_Widget extends Magazine_Point_Widget_Helper {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$args['id']    = 'magazine-point-tabbed';
			$args['label'] = esc_html__( 'MP: Tabbed', 'magazine-point' );

			$args['widget'] = array(
				'classname'   => 'magazine_point_widget_tabbed',
				'description' => esc_html__( 'Tabbed Widget', 'magazine-point' ),
			);

			$args['fields'] = array(
				'popular_heading' => array(
					'label'   => esc_html__( 'POPULAR', 'magazine-point' ),
					'type'    => 'heading',
					),
				'popular_number' => array(
					'label'   => esc_html__( 'No of Posts:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 5,
					'min'     => 1,
					'max'     => 20,
					),
				'popular_thumbnail' => array(
					'label'   => esc_html__( 'Show Thumbnail', 'magazine-point' ),
					'type'    => 'checkbox',
					'default' => true,
					),
				'recent_heading' => array(
					'label'   => esc_html__( 'RECENT', 'magazine-point' ),
					'type'    => 'heading',
					),
				'recent_number' => array(
					'label'   => esc_html__( 'No of Posts:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 5,
					'min'     => 1,
					'max'     => 20,
					),
				'recent_thumbnail' => array(
					'label'   => esc_html__( 'Show Thumbnail', 'magazine-point' ),
					'type'    => 'checkbox',
					'default' => true,
					),
				'comment_heading' => array(
					'label'   => esc_html__( 'COMMENT', 'magazine-point' ),
					'type'    => 'heading',
					),
				'comment_number' => array(
					'label'   => esc_html__( 'No of Comments:', 'magazine-point' ),
					'type'    => 'number',
					'default' => 5,
					'min'     => 1,
					'max'     => 20,
					),
				'comment_thumbnail' => array(
					'label'   => esc_html__( 'Show Thumbnail', 'magazine-point' ),
					'type'    => 'checkbox',
					'default' => true,
					),
				);

			parent::create_widget( $args );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$values = $this->get_field_values( $instance );
			$instance_number = $this->number;

			echo $args['before_widget'];

			?>
			<div class="tabs">
				<ul class="tab-links">
					<li class="tab tab-popular active"><a href="<?php echo esc_url( '#tab' . $instance_number . '-1' ); ?>"><i class="fa fa-fire"></i></a></li>
					<li class="tab tab-recent"><a href="<?php echo esc_url( '#tab' . $instance_number . '-2' ); ?>"><i class="fa fa-list"></i></a></li>
					<li class="tab tab-comments"><a href="<?php echo esc_url( '#tab' . $instance_number . '-3' ); ?>"><i class="fa fa-comment"></i></a></li>
				</ul>

				<div class="tab-content">
					<div id="<?php echo esc_attr( 'tab' . $instance_number . '-1' ); ?>" class="tab active">
						<?php
						$qargs = array(
							'posts_per_page'      => absint( $values['popular_number'] ),
							'orderby'             => 'comment_count',
							'no_found_rows'       => true,
							'ignore_sticky_posts' => true,
						);

						$the_query = new WP_Query( $qargs );
						?>
						<?php if ( $the_query->have_posts() ) : ?>

							<div class="popular-list">

								<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
									<div class="popular-item">

										<?php if ( true === $values['popular_thumbnail'] && has_post_thumbnail() ) : ?>
											<div class="popular-item-thumb">
												<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
											</div><!-- .popular-item-thumb -->
										<?php endif; ?>
										<div class="popular-item-text-wrap">
											<h3 class="popular-item-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											<div class="popular-item-meta entry-meta">
												<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
											</div>
										</div><!-- .popular-item-text-wrap -->
									</div><!-- .popular-item -->
								<?php endwhile; ?>

							</div><!-- .popular-list -->

							<?php wp_reset_postdata(); ?>

						<?php endif; ?>
					</div>

					<div id="<?php echo esc_attr( 'tab' . $instance_number . '-2' ); ?>" class="tab">
						<?php
						$qargs = array(
							'posts_per_page'      => absint( $values['recent_number'] ),
							'no_found_rows'       => true,
							'ignore_sticky_posts' => true,
						);

						$the_query = new WP_Query( $qargs );
						?>
						<?php if ( $the_query->have_posts() ) : ?>

							<div class="latest-list">

								<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
									<div class="latest-item">

										<?php if ( true === $values['recent_thumbnail'] && has_post_thumbnail() ) : ?>
											<div class="latest-item-thumb">
												<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
											</div><!-- .latest-item-thumb -->
										<?php endif; ?>
										<div class="latest-item-text-wrap">
											<h3 class="latest-item-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											<div class="latest-item-meta entry-meta">
												<span class="posted-on"><?php the_time( get_option( 'date_format' ) ); ?></span>
											</div>
										</div><!-- .latest-item-text-wrap -->
									</div><!-- .latest-item -->
								<?php endwhile; ?>

							</div><!-- .latest-list -->

							<?php wp_reset_postdata(); ?>

						<?php endif; ?>
					</div>

					<div id="<?php echo esc_attr( 'tab' . $instance_number . '-3' ); ?>" class="tab">
						<?php
						$qargs = array(
							'number'    => absint( $values['comment_number'] ),
							'post_type' => 'post',
							'status'    => 'approve',
						);
						$comments_query = new WP_Comment_Query;
						$comments = $comments_query->query( $qargs );
						?>
						<?php if ( $comments ) : ?>

							<div class="comment-list">

								<?php foreach ( $comments as $comment ) : ?>
									<div class="comment-item">

										<?php if ( true === $values['comment_thumbnail'] ) : ?>
											<div class="comment-item-thumb">
												<?php echo get_avatar( $comment->comment_author_email, 100 ); ?>
											</div><!-- .comment-item-thumb -->
										<?php endif; ?>

										<div class="comment-item-text-wrap">
											<h3 class="comment-item-title">
												<?php $author_url = get_comment_author_url( $comment ); ?>
												<?php if ( ! empty( $author_url ) ) : ?>
													<strong>	<a href="<?php echo esc_url( $author_url ); ?>">
														<?php echo esc_html( $comment->comment_author ); ?>
													</a></strong>
													<?php esc_html_e( 'on', 'magazine-point' ); ?>
												<?php else : ?>
													<strong><?php echo esc_html( $comment->comment_author ); ?></strong>
													<?php esc_html_e( 'on', 'magazine-point' ); ?>
												<?php endif; ?>
												<?php if ( absint( $comment->comment_post_ID ) > 0 ) : ?>
													<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
													<?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?>
													</a>
												<?php endif; ?>
											</h3>
										</div><!-- .comment-item-text-wrap -->
									</div><!-- .comment-item -->

								<?php endforeach; ?>

							</div><!-- .comment-list -->

						<?php endif; ?>
					</div>

				</div>
			</div>

			<?php

			echo $args['after_widget'];

		}

	}

endif;
