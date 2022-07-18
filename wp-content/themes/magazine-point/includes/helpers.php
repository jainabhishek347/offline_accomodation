<?php
/**
 * Helper functions.
 *
 * @package Magazine_Point
 */

if ( ! function_exists( 'magazine_point_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function magazine_point_get_global_layout_options() {
		$choices = array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'magazine-point' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'magazine-point' ),
			'three-columns' => esc_html__( 'Three Columns', 'magazine-point' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'magazine-point' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'magazine_point_get_site_layout_options' ) ) :

	/**
	 * Returns site layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function magazine_point_get_site_layout_options() {
		$choices = array(
			'fluid' => esc_html__( 'Fluid', 'magazine-point' ),
			'boxed' => esc_html__( 'Boxed', 'magazine-point' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'magazine_point_get_breadcrumb_type_options' ) ) :

	/**
	 * Returns breadcrumb type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function magazine_point_get_breadcrumb_type_options() {
		$choices = array(
			'disabled' => esc_html__( 'Disabled', 'magazine-point' ),
			'enabled'  => esc_html__( 'Enabled', 'magazine-point' ),
		);
		return $choices;
	}

endif;


if ( ! function_exists( 'magazine_point_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function magazine_point_get_archive_layout_options() {
		$choices = array(
			'full'    => esc_html__( 'Full Post', 'magazine-point' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'magazine-point' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'magazine_point_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable    True for adding No Image option.
	 * @param array $allowed        Allowed image size options.
	 * @param bool  $show_dimension True for showing dimension.
	 * @return array Image size options.
	 */
	function magazine_point_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;

		$choices = array();

		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'magazine-point' );
		}

		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'magazine-point' );
		$choices['medium']    = esc_html__( 'Medium', 'magazine-point' );
		$choices['large']     = esc_html__( 'Large', 'magazine-point' );
		$choices['full']      = esc_html__( 'Full (original)', 'magazine-point' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ) {
					$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;

if ( ! function_exists( 'magazine_point_get_image_alignment_options' ) ) :

	/**
	 * Returns image options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function magazine_point_get_image_alignment_options() {
		$choices = array(
			'none'   => esc_html_x( 'None', 'alignment', 'magazine-point' ),
			'left'   => esc_html_x( 'Left', 'alignment', 'magazine-point' ),
			'center' => esc_html_x( 'Center', 'alignment', 'magazine-point' ),
			'right'  => esc_html_x( 'Right', 'alignment', 'magazine-point' ),
		);
		return $choices;
	}

endif;
