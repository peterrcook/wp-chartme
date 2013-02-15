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
function plog($d, $desc = '') {
    error_log($desc.': '.print_r($d, true));
}

//// Admin pages
add_action('admin_menu', 'cme_admin_menu');
function cme_admin_menu() {
    plog('adding');
    add_options_page('ChartMe About', 'ChartMe', 'manage_options', 'chartme', 'cme_admin_page');
}

function cme_admin_page() {
    ?>

    <div class="wrap">
    <?php screen_icon('plugins'); ?>
    <h2>ChartMe</h2>
    <h3>About</h3>
    <p>ChartMe enables Google Chart Tools to be embedded within a post.</p>
    <h3>Requirements</h3>
    <p>No requirements, besides visitors to your site having a modern browser that supports SVG.</p>
    <h3>How to use</h3>
    <p>There's two steps to using ChartMe:</p>
    <h4>1. Add your data to a post custom field</h4>
        <p>Create a custom field in your post with a unique name and add your data (JSON format) to its value.</p>
        <p>For example, add a custom field with name <b>mydata</b> and value:</p>
        <pre>
[
  ['Task', 'Hours per Day'],
  ['Work',     11],
  ['Eat',      2],
  ['Commute',  2],
  ['Watch TV', 2],
  ['Sleep',    7]
]
        </pre>
        <p>You can also assign options by adding another custom field with a unique name.</p>
        <p>For example, add a custom field with name <b>myoptions</b> and value:</p>
        <pre>
{
    title: 'My Daily Activities'
}
        </pre>
        <p>The data and options use the JSON format, just as shown in the Google Chart Tools documentation. Data and options can be shared between charts within the same post.</p>
    <h4>2. Add a shortcode where you want your chart to appear</h4>
        <p>Use the <b>chartme</b> shortcode wherever you'd like a chart to appear in your post.</p>
        <p>For example:</p>
        <pre>
[chartme id="chart1" data="mydata" options="myoptions" type="PieChart"]
        </pre>
        <p>You can even use the same data across multiple charts!</p>
        <pre>
[chartme id="chart1" data="mydata" options="myoptions" type="PieChart"]
[chartme id="chart2" data="mydata" type="BarChart"]
[chartme id="chart3" data="mydata" type="LineChart"]
[chartme id="chart4" data="mydata" type="Table" package="table"]
        </pre>
    <h3>How it works</h3>
    <p>Now for the techie bit: ChartMe provides a wrapper around the Google Chart Tools API. In other words, it provides a window into Google Chart Tools, without you having to write any code.</p>
    <p>This means that you need to use some of the syntax that Google Chart Tools uses such as the format used for the data and options.</p>
    <p>To learn more, delve into the Google Chart Tools documentation, or browse some examples.</p>
    <h3>Support</h3>
    <p>This project is entirely self funded so I can only provide limited free support. You can help me out by either making a donation or bringing some work my way!</p>
    </div>

    <?php
}



//// Plugin functionality
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

function cme_add_package($package) {
    global $cme_data;
    if(!array_key_exists('packages', $cme_data)) {
        $cme_data['packages'] = array();
    }
//    plog($cme_data);    
    if(!in_array($package, $cme_data['packages'])) {
        $cme_data['packages'][] = $package;
    }
}

// For each shortcode, add the chart to $cme_data
add_shortcode('chartme', 'cme_handle_shortcode');
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
        $cme_data = array('charts' => array());
    $cme_data['charts'][] = $chart_data;

    //Add package to global
    if(array_key_exists('package', $attr)) {
        cme_add_package($attr['package']);
    } else {
        cme_add_package('corechart');
    }

    return '<div id="'.$attr['id'].'"></div>';
}

add_action('get_footer', 'cme_add_data');
function cme_add_data() {
    global $cme_data;
    if($cme_data)
        wp_localize_script('cme_load', 'cmeParams', $cme_data);    
}
?>