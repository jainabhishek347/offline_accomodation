<?php
/**
 * Plugin recommendation
 *
 * @package Magazine_Point
 */

// Load TGM library.
require_once trailingslashit( get_template_directory() ) . 'vendors/tgm/class-tgm-plugin-activation.php';

if ( ! function_exists( 'magazine_point_register_recommended_plugins' ) ) :

	/**
	 * Register recommended plugins.
	 *
	 * @since 1.0.0
	 */
	function magazine_point_register_recommended_plugins() {

		$plugins = array(
			array(
				'name'     => esc_html__( 'Contact Form 7', 'magazine-point' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'One Click Demo Import', 'magazine-point' ),
				'slug'     => 'one-click-demo-import',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Regenerate Thumbnails', 'magazine-point' ),
				'slug'     => 'regenerate-thumbnails',
				'required' => false,
			),
		);

		$config = array();

		tgmpa( $plugins, $config );

	}

endif;

add_action( 'tgmpa_register', 'magazine_point_register_recommended_plugins' );
