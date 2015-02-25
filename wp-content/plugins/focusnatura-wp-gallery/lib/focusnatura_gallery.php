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


//customize shortcode -> remove_shortcode( "gallery" );
//add_shortcode( "gallery" , "my_own_gallery" );

add_filter( "post_gallery", "focusnatura_gallery", 10, 2 );

function focusnatura_gallery( $output, $attr ) {

		global $post;
		//$output = apply_filters('post_gallery', '', $attr);

		if ( $output != '' ) {
				return $output;
		}
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
						'post_parent'    => $id,
						'post_status'    => 'inherit',
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'order'          => $order,
						'orderby'        => $orderby
				) );
		}
		if ( empty( $attachments ) ) {
				return '';
		}
		if ( is_feed() ) {
				$output = "\n";
				foreach ( $attachments as $att_id => $attachment ) {
						$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
				}

				return $output;
		}
		$itemtag       = tag_escape( $itemtag );
		$captiontag    = tag_escape( $captiontag );
		$columns       = intval( $columns );
		$itemwidth     = $columns > 0 ? floor( 100 / $columns ) : 100;
		$float         = is_rtl() ? 'right' : 'left';
		$selector      = "gallery-";
		$gallery_style = $gallery_div = '';
		if ( apply_filters( 'use_default_gallery_style', true ) ) {
				$gallery_style = "
    <style type='text/css'>
        #" . $selector . " .gallery-item {
            width: " . $itemwidth . "%;
        }
    </style>";
		}
		$size_class  = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-" . $id . " gallery-columns-" . $columns . " gallery-size-" . $size_class . "'>";
		$output      = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );
		$i           = 0;


		/* ====================================
		 * RENDER GALLERY HTML CODE
		 * ==================================== */


		$thumbs_per_row   = 2;
		//thumbs per row -> array index starts at 0
		//$thumbs_per_row   = $thumbs_per_row-1;

		$row= array();
		$rows   = array();
		$rows[] = $row;
		$i      = 1;
		$total_thumbs = count( $attachments );

		$selected_row_index = 0;
		$selected_thumb_index = 0;

		if ( !get_query_var( 'img' ) ) {
				// redirect to first image in gallery
				// reset() -> returns first element in array


					if(is_single() ){

						$first_attachment = reset( $attachments );
						$append_to_url = filter_url_to_appended($first_attachment->post_title);
						wp_redirect( get_permalink( $post->ID ) . 'img/' . $first_attachment->ID .'/'. $append_to_url);
						exit;
					}

		} else {

				//$selected_thumb_index = array_search( get_query_var( 'img' ), array_keys( $attachments ) );

				$selected_thumb_index = get_query_var('img');




				$tc=0;
				foreach($attachments as $id=>$attachment){
						if($tc%($thumbs_per_row) == 0){
								// we are in new row
								$selected_row_index++;
						}
						if ($id == get_query_var( 'img' )) break;
						$tc++;
				}
				$selected_row_index = ( $selected_row_index > 0 )? $selected_row_index-1 :$selected_row_index;

		}

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
				}
;
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
				$next_class=($selected_row_index+1 < count($rows) )?'enabled':'disabled';
				// if we are not on first enable hte prev button;
				$prev_class=($selected_row_index != 0)?'enabled':'disabled';

				$output .= '<div class="thumbs-nav">';
				$output .= '<span class="thumbs-prev '. $prev_class .'"><a href="#"><i class="fa fa-chevron-left"></i></i></a></span>';
				$output .= '<span class="thumbs-next '.$next_class  .'"><a href="#"><i class="fa fa-chevron-right"></i></i></a></span>';
        $output .= '</div>';

		}

		$output .= '</div><!--thumbs-->';

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
