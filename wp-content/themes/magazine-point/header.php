<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Magazine_Point
 */

?><?php
	/**
	 * Hook - magazine_point_action_doctype.
	 *
	 * @hooked magazine_point_doctype - 10
	 */
	do_action( 'magazine_point_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - magazine_point_action_head.
	 *
	 * @hooked magazine_point_head - 10
	 */
	do_action( 'magazine_point_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - magazine_point_action_before.
	 *
	 * @hooked magazine_point_add_top_head_content - 5
	 * @hooked magazine_point_page_start - 10
	 * @hooked magazine_point_skip_to_content - 15
	 */
	do_action( 'magazine_point_action_before' );
	?>

	<?php
	  /**
	   * Hook - magazine_point_action_before_header.
	   *
	   * @hooked magazine_point_header_start - 10
	   */
	  do_action( 'magazine_point_action_before_header' );
	?>
		<?php
		/**
		 * Hook - magazine_point_action_header.
		 *
		 * @hooked magazine_point_site_branding - 10
		 */
		do_action( 'magazine_point_action_header' );
		?>
	<?php
	  /**
	   * Hook - magazine_point_action_after_header.
	   *
	   * @hooked magazine_point_header_end - 10
	   * @hooked magazine_point_add_primary_navigation - 20
	   */
	  do_action( 'magazine_point_action_after_header' );
	?>
	<?php
	/**
	 * Hook - magazine_point_action_before_content.
	 *
	 * @hooked magazine_point_add_breadcrumb - 7
	 * @hooked magazine_point_add_featured_news_block - 8
	 * @hooked magazine_point_content_start - 10
	 */
	do_action( 'magazine_point_action_before_content' );
	?>
	<?php
	  /**
	   * Hook - magazine_point_action_content.
	   */
	  do_action( 'magazine_point_action_content' );
