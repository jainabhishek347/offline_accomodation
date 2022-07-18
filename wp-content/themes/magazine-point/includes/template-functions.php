<?php
/**
 * Template functions.
 *
 * @package Magazine_Point
 */

if ( ! function_exists( 'magazine_point_get_the_excerpt' ) ) :

	/**
	 * Fetch excerpt from the post.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $length      Excerpt length.
	 * @param WP_Post $post_object WP_Post instance.
	 * @return string Excerpt content.
	 */
	function magazine_point_get_the_excerpt( $length, $post_object = null ) {

		global $post;

		if ( is_null( $post_object ) ) {
			$post_object = $post;
		}

		$length = absint( $length );

		if ( 0 === $length ) {
			return;
		}

		$source_content = $post_object->post_content;

		if ( ! empty( $post_object->post_excerpt ) ) {
			$source_content = $post_object->post_excerpt;
		}

		$source_content = strip_shortcodes( $source_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );

		return $trimmed_content;
	}

endif;

if ( ! function_exists( 'magazine_point_breadcrumb' ) ) :

	/**
	 * Breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_breadcrumb() {

		if ( ! function_exists( 'breadcrumb_trail' ) ) {
			require_once trailingslashit( get_template_directory() ) . 'vendors/breadcrumbs/breadcrumbs.php';
		}

		$breadcrumb_args = array(
			'container'   => 'div',
			'show_browse' => false,
		);

		breadcrumb_trail( $breadcrumb_args );

	}

endif;

if ( ! function_exists( 'magazine_point_fonts_url' ) ) :

	/**
	 * Return fonts URL.
	 *
	 * @since 1.0.0
	 * @return string Font URL.
	 */
	function magazine_point_fonts_url() {

		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Magra, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Magra font: on or off', 'magazine-point' ) ) {
			$fonts[] = 'Magra:400,700';
		}

		/* translators: If there are characters in your language that are not supported by Gudea, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Gudea font: on or off', 'magazine-point' ) ) {
			$fonts[] = 'Gudea:100,400,500,600';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;

	}

endif;

if ( ! function_exists( 'magazine_point_get_sidebar_options' ) ) :

	/**
	 * Get sidebar options.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_get_sidebar_options() {

		global $wp_registered_sidebars;

		$output = array();

		if ( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ) {
			foreach ( $wp_registered_sidebars as $key => $sidebar ) {
				$output[ $key ] = $sidebar['name'];
			}
		}

		return $output;

	}

endif;

if ( ! function_exists( 'magazine_point_primary_navigation_fallback' ) ) :

	/**
	 * Fallback for primary navigation.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_primary_navigation_fallback() {
		echo '<ul>';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'magazine-point' ) . '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 5,
		) );
		echo '</ul>';
	}

endif;

if ( ! function_exists( 'magazine_point_the_custom_logo' ) ) :

	/**
	 * Render logo.
	 *
	 * @since 2.0
	 */
	function magazine_point_the_custom_logo() {

		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}

	}

endif;

if ( ! function_exists( 'magazine_point_render_select_dropdown' ) ) :

	/**
	 * Render select dropdown.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $main_args Main arguments.
	 * @param string $callback Callback method.
	 * @param array  $callback_args Callback arguments.
	 * @return string Rendered markup.
	 */
	function magazine_point_render_select_dropdown( $main_args, $callback, $callback_args = array() ) {

		$defaults = array(
			'id'          => '',
			'name'        => '',
			'selected'    => 0,
			'echo'        => true,
			'add_default' => false,
		);

		$r = wp_parse_args( $main_args, $defaults );
		$output = '';
		$choices = array();

		if ( is_callable( $callback ) ) {
			$choices = call_user_func_array( $callback, $callback_args );
		}

		if ( ! empty( $choices ) || true === $r['add_default'] ) {

			$output = "<select name='" . esc_attr( $r['name'] ) . "' id='" . esc_attr( $r['id'] ) . "'>\n";
			if ( true === $r['add_default'] ) {
				$output .= '<option value="">' . esc_html__( 'Default', 'magazine-point' ) . '</option>\n';
			}
			if ( ! empty( $choices ) ) {
				foreach ( $choices as $key => $choice ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ';
					$output .= selected( $r['selected'], $key, false );
					$output .= '>' . esc_html( $choice ) . '</option>\n';
				}
			}
			$output .= "</select>\n";
		}

		if ( $r['echo'] ) {
			echo $output;
		}

		return $output;
	}

endif;

if ( ! function_exists( 'magazine_point_get_numbers_dropdown_options' ) ) :

	/**
	 * Returns numbers dropdown options.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $min    Min.
	 * @param int    $max    Max.
	 * @param string $prefix Prefix.
	 * @param string $suffix Suffix.
	 * @return array Options array.
	 */
	function magazine_point_get_numbers_dropdown_options( $min = 1, $max = 4, $prefix = '', $suffix = '' ) {

		$output = array();

		if ( $min <= $max ) {
			for ( $i = $min; $i <= $max; $i++ ) {
				$string = $prefix . $i . $suffix;
				$output[ $i ] = $string;
			}
		}

		return $output;

	}

endif;

if ( ! function_exists( 'magazine_point_message_front_page_widget_area' ) ) :

	/**
	 * Show default message in front page widget area.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_message_front_page_widget_area() {

		// Welcome.
		$args = array(
			'title'  => esc_html__( 'Welcome to Magazine Point', 'magazine-point' ),
			'filter' => true,
			'text'   => esc_html__( 'You are seeing this because there is no any widget in Front Page Widget Area. Go to Appearance->Widgets in admin panel to add widgets. This widget will be replaced when you start adding widgets in that widget area.', 'magazine-point' ),
		);

		$widget_args = array(
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			'before_widget' => '<aside class="widget widget_text"><div class="container">',
			'after_widget'  => '</div></aside>',
		);

		the_widget( 'WP_Widget_Text', $args, $widget_args );
	}

endif;

if ( ! function_exists( 'magazine_point_get_single_post_category' ) ) :

	/**
	 * Get single post category.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Post ID.
	 * @return array Category detail.
	 */
	function magazine_point_get_single_post_category( $id ) {
		$output = array();

		$cats = get_the_category( $id );

		if ( ! empty( $cats ) ) {
			$cat  = array_shift( $cats );
			$output['name'] = $cat->name;
			$output['slug'] = $cat->name;
			$output['url']  = get_term_link( $cat );
		}

		return $output;
	}

endif;

if ( ! function_exists( 'magazine_point_show_breaking_news' ) ) :

	/**
	 * Show breaking news.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_show_breaking_news() {
		$show_breaking_news = magazine_point_get_option( 'show_breaking_news' );

		if ( true !== $show_breaking_news ) {
			return;
		}

		$breaking_news_category = magazine_point_get_option( 'breaking_news_category' );
		$breaking_news_text     = magazine_point_get_option( 'breaking_news_text' );
		$breaking_news_number   = magazine_point_get_option( 'breaking_news_number' );
		?>

		<div class="top-news">
			<?php if ( ! empty( $breaking_news_text ) ) : ?>
				<span class="top-news-title"><?php echo esc_html ( $breaking_news_text ); ?></span>
			<?php endif; ?>
			<?php
			$qargs = array(
				'posts_per_page'      => absint( $breaking_news_number ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				);

			if ( absint( $breaking_news_category ) > 0 ) {
				$qargs['cat'] = absint( $breaking_news_category );
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>
				<div id="notice-ticker">
					<div class="notice-inner-wrap">
						<div class="breaking-news-list">
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</div><!-- .breaking-news-list -->
					</div> <!-- .notice-inner-wrap -->
				</div><!-- #notice-ticker -->

			<?php endif; ?>

		</div> <!--.top-news -->
		<?php
	}

endif;
