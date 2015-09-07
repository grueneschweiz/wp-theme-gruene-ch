<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Gruene Theme
 */


if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'gruene' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'gruene_language_nav' ) ) :
/**
 * echos the language menu
 * 
 * adds the .current-site class to correspondig elements
 */
function gruene_language_nav() {
	$nav = wp_nav_menu( array(
		'theme_location' => 'language', 
		'menu_id'        => 'language-menu', 
		'depth'          => -1,
		'echo'           => false,
	) );
	
	$esc_url = str_replace( '/', '\/', get_site_url() );
	$current = '/<li[^>]*>\s*<a href="'.$esc_url.'[^>]*>[^<]*<\/a>\s*<\/li>/';
	
	$result = preg_match( $current, $nav, $match );
	
	// if current site url was found in menu list, add .current-site class
	if ( 1 == $result ) {
		$replacement = preg_replace( '/(class="[^"]*)/', '$1 current-site', $match[0] );
		$nav = preg_replace( $current, $replacement, $nav, 1 );
	}
	
	echo $nav;
}
endif;


if ( ! function_exists( 'gruene_the_post_thumbnail' ) ) :
/**
 * custom version of the_post_thumbnail() // no attributes supportet!
 * 
 * echos out post thumbnail or default image if thumbnail wasn't given
 */
function gruene_the_post_thumbnail() {
	if ( has_post_thumbnail() ) {
		// echo the given thumbnail
		echo the_post_thumbnail();
	} else {
		// if thumbnail is missing
		
		// get header image
		$url = get_header_image();
		$width = esc_attr( get_custom_header()->width ) / 4; // divide by 2 to make it retina compatible
		$height = esc_attr( get_custom_header()->height ) / 4; // divide by 2 to make it retina compatible
	
		$style = "background-image: url('$url'); background-size: {$width}px {$height}px;";
		
		// echo the wrapped image
		echo '<div class="attachment-post-thumbnail attachment-default-post-thumbnail" style="'.$style.'"></div>';
	}
}
endif;


if ( ! function_exists( 'gruene_the_featured_image' ) ) :
/**
 * echos out the featured image with caption if provided
 */
function gruene_the_featured_image() {
	if ( ! has_post_thumbnail() ) {
		// if there is no featured image end here
		return; // BEAKPOINT
	}
	
	// get the image caption
	$caption = get_post( get_post_thumbnail_id() )->post_excerpt;
	
	// if a caption is provided wrap it in div container, else leave it blank
	if ( ! empty( $caption ) ) {
		$caption_html  = '<figcaption class="wp-caption-text">'.$caption.'</figcaption>';
		$caption_class = ' wp-caption';
	} else {
		$caption_html  = '';
		$caption_class = '';
	}
	
	// get the image properties
	$image_props = wp_get_attachment_image_src( get_post_thumbnail_id(), 'gruene_large_post_thumbnail' );
	
	// get the image
	$image_html = get_the_post_thumbnail( get_the_ID(), 'gruene_large_post_thumbnail' );
	
	echo '<figure class="featured-image'.$caption_class.'" style="width:'.$image_props[1].'px;">'.$image_html.$caption_html.'</figure>';
}
endif;


if ( ! function_exists( 'gruene_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function gruene_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>'; //<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )//,
		//esc_attr( get_the_modified_date( 'c' ) ),
		//esc_html( get_the_modified_date() )
	);

	$posted_on = /*sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'gruene' ),*/
		$time_string;
	//);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'gruene' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; //<span class="byline"> ' . $byline . '</span>';

}
endif;


if ( ! function_exists( 'gruene_get_read_more_link' ) ) :
/**
 * returns the read more link HTML
 */
function gruene_get_read_more_link() {
	return '<a class="read-more" href="' . get_permalink( get_the_ID() ) . '">&rarr;&nbsp;' . __( 'Read More', 'gruene' ) . '</a>';
}
endif;

if ( ! function_exists( 'gruene_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function gruene_entry_footer() {
	/**
	 * Uncomment to show category or tags
	 */
	
	//// Hide category and tag text for pages.
	//if ( 'post' == get_post_type() ) {
	//	/* translators: used between list items, there is a space after the comma */
	//	$categories_list = get_the_category_list( esc_html__( ', ', 'gruene' ) );
	//	if ( $categories_list && gruene_categorized_blog() ) {
	//		printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'gruene' ) . '</span>', $categories_list ); // WPCS: XSS OK
	//	}
	//
	//	/* translators: used between list items, there is a space after the comma */
	//	$tags_list = get_the_tag_list( '', esc_html__( ', ', 'gruene' ) );
	//	if ( $tags_list ) {
	//		printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'gruene' ) . '</span>', $tags_list ); // WPCS: XSS OK
	//	}
	//}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'gruene' ), esc_html__( '1 Comment', 'gruene' ), esc_html__( '% Comments', 'gruene' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'gruene' ), '<span class="edit-link button">', '</span>' );
}
endif;


if ( ! function_exists( 'gruene_the_back_button' ) ) :
/**
 * echos a back-button if the referer is internal
 */
function gruene_the_back_button() {
	// the referer url
	$referer = wp_get_referer();
	
	// if the referer url contains the home url --> if internal
	if ( false !== strpos( $referer, home_url() ) ) {
		echo '<div><a class="back-button" href="' . esc_attr( $referer ) . '">&larr;&nbsp;' . __( 'Back', 'gruene' ) . '</a></div>';
	}
}
endif;


if ( ! function_exists( 'gruene_the_archive_title' ) ) :
/**
 * like the_archive_title()
 * but dont show 'Category: ' 
 * 
 * @param string $before can be any string supposed to be echoed before the title
 * @param string $after  can be any string supposed to be echoed after the title 
 */
function gruene_the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} else {
		$title = get_the_archive_title();
	}
	echo $before . esc_html( $title ) .$after;
}

endif;


if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function gruene_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'gruene_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'gruene_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so gruene_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so gruene_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in gruene_categorized_blog.
 */
function gruene_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'gruene_categories' );
}
add_action( 'edit_category', 'gruene_category_transient_flusher' );
add_action( 'save_post',     'gruene_category_transient_flusher' );
