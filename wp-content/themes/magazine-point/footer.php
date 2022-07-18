<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Magazine_Point
 */

	/**
	 * Hook - magazine_point_action_after_content.
	 *
	 * @hooked magazine_point_content_end - 10
	 */
	do_action( 'magazine_point_action_after_content' );
?>

	<?php
	/**
	 * Hook - magazine_point_action_before_footer.
	 *
	 * @hooked magazine_point_add_footer_widgets - 5
	 * @hooked magazine_point_footer_start - 10
	 */
	do_action( 'magazine_point_action_before_footer' );
	?>
	<?php
	  /**
	   * Hook - magazine_point_action_footer.
	   *
	   * @hooked magazine_point_footer_copyright - 10
	   */
	  do_action( 'magazine_point_action_footer' );
	?>
	<?php
	/**
	 * Hook - magazine_point_action_after_footer.
	 *
	 * @hooked magazine_point_footer_end - 10
	 */
	do_action( 'magazine_point_action_after_footer' );
	?>

<?php
	/**
	 * Hook - magazine_point_action_after.
	 *
	 * @hooked magazine_point_page_end - 10
	 * @hooked magazine_point_footer_goto_top - 20
	 */
	do_action( 'magazine_point_action_after' );
?>

<?php wp_footer(); ?>
</body>
</html>
