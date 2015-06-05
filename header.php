<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Gruene Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gruene' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="header-top-section">
			<div class="site-branding">
				<?php if ( get_header_image() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"></a>
				<?php endif; // End header image check. ?>
			</div><!-- .site-branding -->
			
			<nav id="language-switch" class="language-navigation navigation" role="navigation">
				<?php gruene_language_nav(); ?>
			</nav><!-- #language-switch -->
			
			<nav id="meta-navigation" class="meta-navigation navigation" role="navigation">
				<?php wp_nav_menu( array( 
					'theme_location' => 'meta', 
					'menu_id' => 'meta-menu', 
					'after'   => '<span class="meta-navigation-separator">|</span>',
					'depth'   => -1,
				) ); ?>
			</nav><!-- #meta-navigation -->
			
			<div id="header-search-form">
				<?php get_search_form(); ?>
			</div><!-- #header-search-form -->
			
			<div id="mobile-nav-toggle">
				<span class="mobile-nav-symbol"></span>
				<span class="mobile-nav-text"><?php _e( 'Navigation', 'gruene' ); ?></span>
			</div><!-- #mobile-nav-toggle -->
			
		</div><!-- .header-top-section -->
		
		<div class="clear"></div>
		
		<?php if( is_home() ) : ?>
			<div id="gruene-slider" class="image-slider">
				<?php gruene_slider(); ?>
			</div><!-- #slider -->
		<?php endif; // End if is_home() ?>
		
		<nav id="site-navigation" class="main-navigation navigation" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'depth' => 3 ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<nav id="side-menu" role="navigation">
		<div id="close-side-menu"></div>
		<div id="side-menu-title"><?php _e( 'Menu', 'NaSe-Theme' ); ?></div>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu-mobile', 'depth' => 3 ) ); ?>
		<?php gruene_language_nav(); ?>
	</nav>

	<div id="content" class="site-content">
