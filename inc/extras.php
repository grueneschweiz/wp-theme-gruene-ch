<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extras for this theme.
 *
 * @package Gruene Theme
 */

if ( ! function_exists( 'gruene_hide_n_show' ) ) :
/**
 * Add shortcode for slidetoggle content
 * 
 * Use [hide_n_show display="The title"]The content[/hide_n_show]
 * in the editor to slide toggle some content. Replace 'The title'
 * with the caption of the togglebutton and 'The content' with
 * the content to slide in or out. When the page ist loaded,
 * the content is hidden.
 * Use the conditional arguemnts 'css' to add some custom styles
 * and 'add_class' to add some custom classes.
 */
function gruene_hide_n_show( $atts, $content = '' ) {
	extract( 
		shortcode_atts( 
			array( 
				'display' 			=> 'hide_n_show display="[Enter your display text here]"',
				'css'				=> '',
				'class'				=> 'gruene_hide_n_show',
				'add_class'			=> '',
			),
			$atts
		)
	);
	
	return 	'<div class="'.$class.' '.$add_class.'" style="'.$css.'">'
				.'<div class="gruene_hide_n_show_display"><h2><a href="#">'.$display.'</a><h2></div>'
				.'<div class="gruene_hide_n_show_content">' . do_shortcode( $content ) . '</div>'
			.'</div>';
}
endif;
add_shortcode( 'hide_n_show', 'gruene_hide_n_show' );

