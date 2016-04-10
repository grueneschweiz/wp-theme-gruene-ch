<?php
/**
 * The template used for displaying page content in campaign.php
 *
 * @package Gruene Theme
 * @since 2.0.0
 */
?>

<?php
/**
 * Content block for the lightbox
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'gruene-campaign' ); ?>>
	<header class="entry-header">
		
		<?php gruene_the_featured_image(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'gruene' ), '<span class="edit-link button">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->