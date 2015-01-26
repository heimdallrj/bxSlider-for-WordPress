<?php
/*
Plugin Name: bxSlider for WordPress
Plugin URI: http://www.github.com/thinkholic/bxSlider-for-WordPress
Description: A plugin - Intgration jQuery bxSlider for WordPress.
Version: 0.1
Author: Ind (_thinkholic)
Author URI: http://www.github.com/thinkholic/
License: GPL2
*/

# Post Type Registration

function custom_post_type_slider()
{
	$labels = array(
		'name'               => _x( 'Slider', 'post type general name' ),
		'singular_name'      => _x( 'Slide', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'Slide' ),
		'add_new_item'       => __( 'Add New Slide' ),
		'edit_item'          => __( 'Edit Slide' ),
		'new_item'           => __( 'New Slide' ),
		'all_items'          => __( 'All Slides' ),
		'view_item'          => __( 'View Slide' ),
		'search_items'       => __( 'Search Slides' ),
		'not_found'          => __( 'No slides found' ),
		'not_found_in_trash' => __( 'No slides found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Slider'
	);

	$args = array(
		'labels'        => $labels,
		'description'   => 'WP Image Slider',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'thumbnail' ),
		'has_archive'   => true,
	);

	register_post_type( 'slides', $args ); 
}
add_action( 'init', 'custom_post_type_slider' );

// Scripts / Styles
if ( !is_admin() )
{
	// jQuery
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("http://code.jquery.com/jquery-latest.js"), false);
	wp_enqueue_script('jquery');
	
	// bxSlider
	wp_register_script('bxsliderjs', (plugin_dir_url( __FILE__ )."js/jquery.bxslider.min.js"), false);
	wp_enqueue_script('bxsliderjs');
	
	// bxSlide Styles
	wp_register_style( 'bxslidercss', plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css', false );
	wp_enqueue_style( 'bxslidercss' );
}

// Add Handler JS
function bxscript() {
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.bxslider').bxSlider();
	});
</script>
<?php
}
add_action( 'wp_footer', 'bxscript' );

// Pluging Output
function bxSlider()
{
	$args = array(
        "post_type" => "slides",
        "order" => "DESC"
    );
	
	// The Query
	$the_query = new WP_Query( $args );
	
	$op = '';
	
	// The Loop
    if ( $the_query->have_posts() )
    {
		$op .= '<ul class="bxslider">';
		
        while ( $the_query->have_posts() )
        {
			$the_query->the_post();
			
			$op .= '<li><img src="'.wp_get_attachment_url (get_post_thumbnail_id( get_the_ID() )).'" /></li>';
        }
		
		$op .= '</ul>';
    }
    else
    {
        $op = '<p>No Image Slide(s) Found.';
    }
	
	print $op;
}

// EOF.