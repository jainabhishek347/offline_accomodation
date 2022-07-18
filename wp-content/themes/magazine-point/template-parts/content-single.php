<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Magazine_Point
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	  /**
	   * Hook - magazine_point_single_image.
	   *
	   * @hooked magazine_point_add_image_in_single_display - 10
	   */
	  do_action( 'magazine_point_single_image' );
	?>
	<div class="article-wrapper">

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<div class="entry-meta">
				<?php magazine_point_posted_on(); ?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'magazine-point' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer entry-meta">
			<?php magazine_point_entry_footer(); ?>
		</footer><!-- .entry-footer -->

	</div> <!-- .article-wrapper -->

</article><!-- #post-## -->

