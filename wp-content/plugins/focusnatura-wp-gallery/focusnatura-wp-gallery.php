<?php
/* Plugin Name: focusnatura-wp-gallery
Plugin URI: http://webopius.com/
Description: adds a rewrite endpoint and customized links to gallery thumbnails created with the built in wp_gallery /post-name/img/1 -> query_vars['img' => 1]
use in template: get_query_vars('img') // 1
Author: MR
Version: 1.0
Author URI:
*/


add_action('init', 'app_output_buffer');
function app_output_buffer() {

  ob_start();

} // soi_output_buffer



require ('lib/focusnatura_gallery_shortcode.php');

/*add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars )
{
    $qvars[] = ' myvar';
    return $qvars;
}*/

/*add_filter('init','flushRules');

// Remember to flush_rules() when adding rules
function flushRules()
{
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
}*/


//add_action('parse_query', 'fn_parse_request');
//add_action('pre_get_posts', 'fn_parse_request');
//add_action('the_post', 'fn_parse_request');
function fn_parse_request ($data){
   /* echo '<pre>';
    var_dump($data);
    echo '</pre>';*/

    global $post;

    echo $post->post_content;

    echo get_post_gallery($post->ID);


    exit;
}



/**
 * ENDPOINT
 * rewrites /img/6 to ?img=6 -> get_query_var('img') = 6
 */


add_action( 'init', 'focusnatura_gallery_endpoint' );
function focusnatura_gallery_endpoint(){
    // rewrites /img/6 to ?img=6 -> get_query_var('img') = 6
    add_rewrite_endpoint( 'img', EP_PERMALINK | EP_PAGES);
}

add_filter( 'query_vars', 'focusnatura_query_vars' );
function focusnatura_query_vars( $query_vars ){
    // add related to the array of recognized query vars
    $query_vars[] = 'img';
    return $query_vars;
}

/**
 * TEMPLATE
 */

//add_filter( 'template_include', 'check_for_gallery' );

function check_for_gallery( $original_template ) {
    if ( get_post_gallery() ) {
        //has gallery if not working try -> plugin_dir_path( __FILE__ )
        return plugin_dir_path( __FILE__ ).'templates/focusnatura-content-gallery.php';
    } else {
        return $original_template;
    }
}

/**
 * CSS / JS
 */

add_action( 'wp_enqueue_scripts', 'focusnatura_gallery_scripts' );

function focusnatura_gallery_scripts() {
    wp_register_style( 'focusnatura-gallery-css',  plugin_dir_url( __FILE__ ) . 'css/focusnatura-gallery.css',array(), null );
    wp_enqueue_style( 'focusnatura-gallery-css' );
    wp_register_style( 'font-awesome-css',  'http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',array(), null );
    wp_enqueue_style( 'font-awesome-css' );
    wp_register_script('focusnatura-gallery-js', plugin_dir_url( __FILE__ ) . 'js/focusnatura-gallery.js',array('jquery'), null, true );
    wp_enqueue_script( 'focusnatura-gallery-js' );
}



