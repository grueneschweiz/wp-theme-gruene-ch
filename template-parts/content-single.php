<?php
/**
 * @package Gruene Theme
 */
?>

<?php if ( 'short' == get_theme_mod( 'title_length', 'normal' ) ) : ?>
	<?php the_title( '<h1 class="entry-title entry-title-short">', '</h1>' ); ?>

	<?php if ( 'post' == get_post_type() && ! is_sticky() ) : ?>
        <div class="entry-meta entry-meta-title-short">
            <?php gruene_posted_on( true); ?>
        </div><!-- .entry-meta -->
    <?php endif; ?>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header gruene-title_length-<?php echo get_theme_mod( 'title_length', 'normal' ); ?>">
		<?php if ( 'normal' == get_theme_mod( 'title_length', 'normal' ) ) : ?>

			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<?php if ( 'post' == get_post_type() && ! is_sticky() ) : ?>
                <div class="entry-meta">
					<?php gruene_posted_on(); ?>
                </div><!-- .entry-meta -->
			<?php endif; ?>

		<?php endif; ?>

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
