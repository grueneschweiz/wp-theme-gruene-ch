<?php
/**
 * @package Gruene Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		
		<div class="entry-meta">
			<?php gruene_posted_on(); ?>
		</div><!-- .entry-meta -->
		
		<?php gruene_the_featured_image(); ?>
		
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'gruene' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php gruene_the_back_button(); ?>
		<?php gruene_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
