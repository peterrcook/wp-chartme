<?php
/*
Plugin Name: ChartMe
Plugin URI: http://prcweb.co.uk
Description: A Google Chart Tools wrapper, allowing a chart to be embedded within a post
Version: 1.0
Author: Peter Cook
Author URI: http://prcweb.co.uk
License: GPLv2 or later
*/                                                                               
?>
<?php

// Error logging wrapper
// function plog($d, $desc = '') {
//     error_log($desc.': '.print_r($d, true));
// }

// Global to contain the aggregate of the query's charts.
// This will be sent to the client as json using wp_localize_script
$cme_data = null;

add_action('wp_enqueue_scripts', 'cme_enqueue_scripts');
function cme_enqueue_scripts() {
    wp_register_script('jsapi', 'https://www.google.com/jsapi');
    wp_enqueue_script('jsapi');

    //This plugin's Javascript
    wp_register_script('cme_load', plugin_dir_url( __FILE__ ).'/js/cme_load.js', array('jsapi'), '', true);
    wp_enqueue_script('cme_load');
}

// For each shortcode, add the chart to $cme_data
add_shortcode('cme', 'cme_handle_shortcode');
function cme_handle_shortcode($attr) {
    // Pull in data and options from the post metadata
    $chart_data = array();
    $meta = get_post_custom();

    // Use id-data metadata if attr['data'] not specified
    if(array_key_exists('data', $attr))
        $data = $meta[$attr['data']][0];
    else
        $data = $meta[$attr['id'].'-data'][0];

    // Use id-options metadata if attr['options'] not specified
    if(array_key_exists('options', $attr))
        $options = $meta[$attr['options']][0];
    else
        $options = $meta[$attr['id'].'-options'][0];

    //Convert single quotes to double
    if(!(array_key_exists('fixquotes', $attr) && $attr['fixquotes'] == 'false')) {
        $data = str_replace("'", '"', $data);
        $options = str_replace("'", '"', $options);
    }

    $chart_data['data'] = json_decode($data);
    $chart_data['options'] = json_decode($options);

    // Bundle in shortcode attributes (e.g. chart type)
    $chart_data['attr'] = $attr;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                

    // Write to global
    global $cme_data;
    if(!$cme_data)
        $cme_data = array();
    $cme_data[] = $chart_data;

    return '<div id="'.$attr['id'].'"></div>';
}

add_action('get_footer', 'cme_add_data');
function cme_add_data() {
    global $cme_data;
    if($cme_data)
        wp_localize_script('cme_load', 'cmeParams', $cme_data);    
}
?>