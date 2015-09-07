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
<meta name="theme-color" content="#84b414">

<?php gruene_the_site_icon_fallback(); // echo the default fav-icon if none was defined in the theme customizer ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gruene' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="header-top-section">
			<div class="site-branding">
				<?php if ( get_header_image() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" alt="Logo" id="logo"></a>
				<?php endif; // End header image check. ?>
				<?php if ( ! has_nav_menu( 'language' ) ) : // If there is no language menu, use the space for an additional image ?>
					<?php $img_id = get_theme_mod( 'additional_header_image', false ); // get the attachment image id ?>
					<?php $img = wp_get_attachment_image_src( $img_id, array(500, 308) ); ?>
					<?php if ( ! empty( $img ) ) : // if an additional_header_image was set up ?>
						<div id="additional-header-image">
							<img src="<?php echo $img[0]; ?>" width="<?php echo $img[1]/2; ?>" height="<?php echo $img[2]/2; ?>">
						</div>
					<?php endif; // End if ! empty( $img ) ?>
				<?php endif; // End if ! has_nav_menu( 'language' ) ?>
			</div><!-- .site-branding -->
			
			<?php if ( has_nav_menu( 'language' ) ) : ?>
				<nav id="language-switch" class="language-navigation navigation" role="navigation">
					<?php gruene_language_nav(); ?>
				</nav><!-- #language-switch -->
			<?php endif; // End if has_nav_menu( 'language' ) ?>
			
			<div id="header-search-form">
				<?php get_search_form(); ?>
			</div><!-- #header-search-form -->
			
			<?php if ( has_nav_menu( 'meta' ) ) : ?>
				<nav id="meta-navigation" class="meta-navigation navigation" role="navigation">
					<?php wp_nav_menu( array( 
						'theme_location' => 'meta', 
						'menu_id' => 'meta-menu', 
						'after'   => '<span class="meta-navigation-separator">|</span>',
						'depth'   => -1,
					) ); ?>
				</nav><!-- #meta-navigation -->
			<?php endif; // End if has_nav_menu( 'meta' ) ?>
			
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<div id="mobile-nav-toggle">
					<span class="mobile-nav-symbol"></span>
					<span class="mobile-nav-text"><?php _e( 'Navigation', 'gruene' ); ?></span>
				</div><!-- #mobile-nav-toggle -->
			<?php endif; // End if has_nav_menu( 'primary' ) ?>
			
		</div><!-- .header-top-section -->
		
		<div class="clear"></div>
		
		<?php if( is_home() ) : ?>
			<div id="gruene-slider" class="image-slider">
				<?php the_cyboslider(); ?>
			</div><!-- #slider -->
		<?php endif; // End if is_home() ?>
		
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'depth' => 3 ) ); ?>
			</nav><!-- #site-navigation -->
		<?php endif; // End if has_nav_menu( 'primary' ) ?>
	</header><!-- #masthead -->

	<nav id="side-menu" role="navigation">
		<div id="close-side-menu"></div>
		<div id="side-menu-title"><?php _e( 'Menu', 'NaSe-Theme' ); ?></div>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu-mobile', 'depth' => 3 ) ); ?>
		<?php gruene_language_nav(); ?>
	</nav>

	<div id="content" class="site-content">
