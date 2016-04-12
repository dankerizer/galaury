<?php
/**
 * Created by PhpStorm.
 * User: dankerizer
 * Date: 12/04/2016
 * Time: 22.33
 */
/*
Plugin Name:   Gallaury (Galau Gallery)
Plugin URI:
Description:   Add your all image attachment in single post
Version: 		1.0.0
Author: 		Hadie Danker
Author URI: 	http://www.dankerizer.com
License:		GPLv2 or later
License URI: 	http://www.dankerizer.com/licence

*/
if ( ! defined( 'ABSPATH' ) ) exit;

define('GALAURY_VERSION', '1.0.00');
define ('GALAURY_URL', plugins_url( '',__FILE__ ));


function galaury_show_gallery($postid = 0,$jumlahimage = 0,$posisi = 'single'){
    global $wpdb;

    $countimage	= $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_parent = $postid AND post_type = 'attachment' AND post_mime_type LIKE 'image/%%';" );
    $post_title     = get_the_title($postid);
    $args  = array(
        'order'          => 'ASC',
        'post_type'      => 'attachment',
        'post_parent'    => $postid,
        'post_mime_type' => 'image',
        'post_status'    => null,
        'numberposts'    => $jumlahimage,
    );
    $atts = get_posts($args);
    if($countimage>1){
        //shuffle($atts);
        $output =  ''.'<div id="gallery">';
        $i = 1;
        foreach($atts as $im) {

            //KONSTRUKSI IMG
            $attid      = $im->ID;
            $attitle    = $im->post_title;
            $caption    = $im->post_excerpt;
            $decription = $im->post_content;
            $post_image = $im->guid;
            $imgs       = wp_get_attachment_image_src($attid, 'full');
            $imgrc      = $imgs[0];
            $img_width  = $imgs[1];
            $img_height  = $imgs[2];
            $atturl = get_attachment_link($attid);
            //KONSTRUKSI ALT

            $attitle   = str_replace(array('-','_'),' ',$attitle);
            //$alt     = $title.': '.$attitle;
            $tagid  = "caption$i";
            $output .= '<div id="attachment_'.$attid.'"  class="wp-caption aligncenter"><img class="size-full img-responsive wp-image-'.$attid.'" src="'.$post_image.'" alt="'.$attitle.'" width="'.$img_width.'" height="'.$img_height.'" /><p class="wp-caption-text">'.$attitle.'</p> <p>'.$decription.'</p></div>';
            $i++;
        }

        $output .= ''.'</div>';
    }else{
        $output = '';
    }
    return $output;
}

function galaury_insert_gallery($content){
    global $post;
    //$content    = get_the_content($post->ID);
    $output =  $content;
    if ( is_single() && ! is_admin() ) {
        // return galibox_show_gallery($post->ID,11,100);
        $output .='darisinimulainya';
        $output .=  galaury_show_gallery($post->ID,11,100);
        //$content .=  $content;


    }

    return $output;
}
add_filter( 'the_content', 'galaury_insert_gallery',1 );
