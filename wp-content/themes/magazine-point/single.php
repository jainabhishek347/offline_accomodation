<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Magazine_Point
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			<?php the_post_navigation(); ?>

			<?php
				/**
				 * Hook - magazine_point_action_related_posts.
				 *
				 * @hooked: magazine_point_add_related_posts - 10
				 */
				do_action( 'magazine_point_action_related_posts' );
			?>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * Hook - magazine_point_action_sidebar.
	 *
	 * @hooked: magazine_point_add_sidebar - 10
	 */
	do_action( 'magazine_point_action_sidebar' );
?>
<?php get_footer(); ?>
