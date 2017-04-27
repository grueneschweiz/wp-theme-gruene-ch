<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Gruene Theme
 */
?>

</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<?php if ( is_active_sidebar( 'footer-widget-area' ) ): ?>
        <div class="footer-widget-area footer-content">
			<?php dynamic_sidebar( 'footer-widget-area' ); ?>
            <div class="clear"></div>
        </div><!-- .footer-widget-area -->
	<?php endif; ?>
    <div class="site-info footer-content">
        <div id="copy-info">
            &copy; <?php echo date( 'Y' ); ?> <?php echo get_bloginfo( 'name' ); ?>
        </div><!-- #copy-info -->
		
		<?php if ( has_nav_menu( 'footer-meta' ) ) : ?>
            <nav id="footer-meta-navigation" class="meta-navigation navigation" role="navigation">
				<?php wp_nav_menu( array(
					'theme_location' => 'footer-meta',
					'menu_id'        => 'footer-meta-menu',
					'after'          => '<span class="meta-navigation-separator">|</span>',
					'depth'          => - 1,
				) ); ?>
            </nav>
		<?php endif; // End if has_nav_menu( 'footer-meta' ) ?>

        <div class="clear"></div>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<div id="printing-footer" class="print-only">
	<?php
	echo _x( sprintf( 'Printed %s', date_i18n( get_option( 'date_format' ) ) ), 'Printing date', 'gruene' )
	     . '<br><br>'
	     . get_permalink();
	?>
    <style>
        @media print {
            #printing-footer::before {
                content: url('https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=<?php urlencode(the_permalink()); ?>&choe=UTF-8');
            }
        }
    </style>
</div>

<?php wp_footer(); ?>

</body>
</html>