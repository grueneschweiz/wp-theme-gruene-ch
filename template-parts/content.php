<?php
/**
 * @package Gruene Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<header class="entry-header">
		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<?php gruene_the_post_thumbnail(); ?>
		</a>
		
		<?php if ( 'post' == get_post_type() && ! is_sticky() ) : ?>
			<div class="entry-meta">
				<?php gruene_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
		
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		
		<div class="clear"></div>
		
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php gruene_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->