<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Set path to WooFramework and theme specific functions
$functions_path = TEMPLATEPATH . '/functions/';
$includes_path = TEMPLATEPATH . '/includes/';
/*
//add_action('init', 'app_output_buffer');
function app_output_buffer() {

  ob_start();

} // soi_output_buffer*/


/**
 * ENDPOINT
 * rewrites /img/6 to ?img=6 -> get_query_var('img') = 6
 */


add_action('deprecated_function_run', 'logme');
function logme($data){
  echo '<pre>';
  var_dump($data);
  echo '</pre>';

  echo '<pre>';
  var_dump(debug_print_backtrace());
  echo '</pre>';

}


//require_once ('includes/focusnatura_gallery_shortcode.php');


// WooFramework
require_once ($functions_path . 'admin-init.php');			// Framework Init

// Theme specific functionality
require_once ($includes_path . 'theme-options.php'); 		// Options panel settings and custom settings
require_once ($includes_path . 'theme-functions.php'); 		// Custom theme functions
//require_once ($includes_path . 'theme-plugins.php');		// Theme specific plugins integrated in a theme
//require_once ($includes_path . 'theme-actions.php');		// Theme actions & user defined hooks
require_once ($includes_path . 'theme-comments.php'); 		// Custom comments/pingback loop
require_once ($includes_path . 'theme-js.php');				// Load javascript in wp_head
require_once ($includes_path . 'sidebar-init.php');			// Initialize widgetized areas
require_once ($includes_path . 'theme-widgets.php');		// Theme widgets

/*-----------------------------------------------------------------------------------*/
/* End WooThemes Functions - You can add custom functions below */
/*-----------------------------------------------------------------------------------*/

function bm_dont_display_it($content) {
  if (!empty($content)) {
    $content = str_ireplace('<li>' .__( "No categories" ). '</li>', "", $content);
  }
  return $content;
}
add_filter('wp_list_categories','bm_dont_display_it');



?>