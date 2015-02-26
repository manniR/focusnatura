<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 22.02.15
 * Time: 11:46
 */


/**
 * GALLERY
 * rewrites html output for [gallery] shortcode
 * returns $output
 */

//add_filter( "post_gallery", "focusnatura_gallery", 10, 2 );

//customize shortcode -> remove_shortcode( "gallery" );

remove_shortcode('gallery');
remove_shortcode('photoshpace');

add_shortcode( "gallery" , "focusnatura_gallery_shortcode" );
add_shortcode( 'photospace', 'focusnatura_gallery_shortcode' );
add_shortcode( 'focusnatura_gallery', 'focusnatura_gallery_shortcode' );


function focusnatura_gallery_shortcode( $gallery, $attr) {

		global $post;
		//$output = apply_filters('post_gallery', '', $attr);

		$attachment_ids = explode(',',$gallery['ids'] );


	/*	if ( $output != '' ) {
				return $output;
		}*/

		$output = '';



		if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( !$attr['orderby'] ) {
						unset( $attr['orderby'] );
				}
		}

		extract( shortcode_atts( array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'itemtag'    => 'dl',
				'icontag'    => 'dt',
				'captiontag' => 'dd',
				'columns'    => 3,
				'size'       => 'thumbnail',
				'include'    => '',
				'exclude'    => ''
		), $attr ) );

		$id = intval( $id );
		if ( 'RAND' == $order ) {
				$orderby = 'none';
		}
		if ( !empty( $include ) ) {
				$include      = preg_replace( '/[^0-9,]+/', '', $include );
				$_attachments = get_posts( array(
						'include'        => $include,
						'post_status'    => 'inherit',
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'order'          => $order,
						'orderby'        => $orderby
				) );
				$attachments  = array();
				foreach ( $_attachments as $key => $val ) {
						$attachments[ $val->ID ] = $_attachments[ $key ];
				}
		} elseif ( !empty( $exclude ) ) {
				$exclude     = preg_replace( '/[^0-9,]+/', '', $exclude );
				$attachments = get_children( array(
						'post_parent'    => $id,
						'exclude'        => $exclude,
						'post_status'    => 'inherit',
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'order'          => $order,
						'orderby'        => $orderby
				) );
		} else {
				$attachments = get_children( array(
						//'post_parent'    => $id,
						'post_status'    => 'inherit',
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'order'          => $order,
						'orderby'        => $orderby
				) );
		}





		/* ====================================
		 * SETTINGS
		 * ==================================== */


		$thumbs_per_row   = 2;
		//thumbs per row -> array index starts at 0
		//$thumbs_per_row   = $thumbs_per_row-1;

		$row= array();
		$rows   = array();
		$rows[] = $row;
		$i      = 1;
		$total_thumbs = count( $attachments );
		$id = 4;

		$selected_row_index = 0;
		$selected_thumb_index = 0;


						echo '<pre>';
//						var_dump(get_posts(array('post_type' => 'attachment')));
						var_dump($include);
						var_dump(' ----- '.count(get_posts(array('post_type' => 'attachment'))));
						echo '</pre>';


		/* ====================================
     * REDIRECTS
     * ==================================== */

		if ( !get_query_var( 'img' ) ) {

				//redirect to first image in gallery

				if ( is_single() ) {




						//reset() -> returns first element in array

						$first_attachment = reset( $attachments );


						//$append_to_url    = filter_url_to_appended( $first_attachment->post_title );
						//wp_redirect( get_permalink( $post->ID ) . 'img/' . $first_attachment->ID . '/' . $append_to_url );
						//exit;
				}

		} else {

				// calculate additional setting

				$selected_thumb_index = get_query_var('img');

				$tc=0;
				foreach($attachments as $id=>$attachment){
						if($selected_row_index > 0 && $tc%($thumbs_per_row) == 0){
								// we are in new row
								$selected_row_index++;
						}
						if ($id == get_query_var( 'img' ))
								break;

						$tc++;
				}

				//$selected_row_index = ( $selected_row_index > 0 )? $selected_row_index-1 :$selected_row_index;

		}




		/*=========================================
		 * HTML OUTPUT START
		 =========================================*/

		$output .= '<div class="gallery_clear"></div>';
		$output	.= '<div id="gallery_" class="photospace">';
		$output	.= '<!-- Start Advanced Gallery Html Containers -->';
		$output .= '<h1>Focusnatura Gallery </h1>';

		/* TUMBS ===================*/

		$output .= '<div class="thumbs">
                <div id="thumb-container" class="thumbs-container">';

		foreach ( $attachments as $id => $attachment ) {

				$attachment_image = wp_get_attachment_image( $id, $size );
				$attachment_image_src = wp_get_attachment_image_src($id, $size); // returns Array
				$attachment_image_src = $attachment_image_src[0]; // absolute path to image

				// move array pointer to last index
				end($rows);
				// add attachment_id to row array
				$rows[key($rows)][] = $id;

				// post-name/6 (6=attachmentID) if permalinks disabled '?p=9&attachment_id=6
				$linkURL = ( get_option( 'permalink_structure' ) ) ? get_permalink( $post->ID ) . 'img/' . $id : the_permalink() . '&attachement_id=' . $id ;
				$linkURL .= '/'.filter_url_to_appended($attachment->post_title);

				if ( $i == 1 ) {
						$output .= '<ul class="row row-' . count($rows);
						$output .= (key($rows) == $selected_row_index )?' row-selected">' : '">';
				};
				$output .= '<li class="thumb thumb-' . $id .' ';
				$output .= ($selected_thumb_index == $id)? 'thumb-selected"' :'"'; // selected class
				$output .= '">';
				$output .= '<a href="' . $linkURL . '" title="' . $attachment->post_title . '" >';
				$output .= '<span style="background:url(\''. $attachment_image_src .'\') top left no-repeat; background-size:cover;"></span>';
				$output .= $attachment->post_title;
				//$output .= $attachment_image;
				$output .= '</a></li>';

				if( $i <= $total_thumbs){

						if ( $i!=$total_thumbs && $i%$thumbs_per_row == 0 ) {
								// new row but only if there are more thumbs?
								$output .= '</ul>';

								if ( $i < $total_thumbs){
										// and create new row
										$rows[] = array();
										end($rows);
										$output .= '<ul class="row '.$selected_row_index.' row-' . count($rows);
										$output .= (key($rows) == $selected_row_index )?' row-selected">' : '">';

								}

						}

				} else {
						$output .= '</ul>';
				}

				$i ++;
		}

		$output .= '</div><!--thumbs-container-->';

		if ( count( $rows ) > 1 ) {

				// as long as there are rows left - enable the next button
				$next_class=($selected_row_index < count($rows) )?'enabled':'disabled';
				// if we are not on first enable hte prev button;
				$prev_class=($selected_row_index != 0)?'enabled':'disabled';

				$output .= '<div class="thumbs-nav">';
				$output .= '<span class="thumbs-prev '. $prev_class .'"><a href="#"><i class="fa fa-chevron-left"></i></i></a></span>';
				$output .= '<span class="thumbs-next '.$next_class  .'"><a href="#"><i class="fa fa-chevron-right"></i></i></a></span>';
        $output .= '</div>';

		}
		$output .= '</div><!--thumbs-->';
		/* SLIDE ===================*/


		$selected_key = array_search( get_query_var( 'img' ), $attachment_ids );
		$available_keys = count($attachment_ids)-1;

		if ($selected_key < $available_keys){
				// next in gallery
				$next_id = $attachment_ids[$selected_key+1];
		} else {
				// first in gallery
				$next_id = $attachment_ids[0];
		}


		$output .= '<!-- Start Advanced Gallery Html Containers -->';
		$output .= '<div id="slideshow_" class="slideshow">';
		$output	.= '<span class="image-wrapper current">';
		$output .= '<a href="' . get_permalink( $post->ID ) . 'img/'. $next_id . '">';

		$output	.= wp_get_attachment_image( get_query_var( 'img' ), 'full' );
		$output .= '</a></span></div>';



		return $output;
}

/*
 *
	FILTER URL
 */

function filter_url_to_appended($string){

		$string = strtolower($string);
		$string = str_replace(' ', '-', $string);
//		$string = str_replace('Ä', 'ae', $string);
		$string = str_replace('ä', 'ae', $string);
//		$string = str_replace('Ö', 'oe', $string);
		$string = str_replace('ö', 'oe', $string);
//		$string = str_replace('Ü', 'ue', $string);
		$string = str_replace('ü', 'ue', $string);
		$string = str_replace('ẞ', 'ss', $string);
		$string = str_replace('ß', 'ss', $string);

		return $string;

}
