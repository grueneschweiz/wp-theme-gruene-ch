<?php
/**
 * Template Name: Campaign Page
 * 
 * This template is designed to promote a signle topic, issue, petition, etc.
 * The content will open in a dialog box above it's page.
 * 
 * @package WordPress
 * @subpackage Gruene Theme
 * @since 2.0.0
 */

get_header(); ?>
     
	<div id="primary" class="content-area">
		
		<main id="main" class="site-main" role="main">
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'campaign' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
