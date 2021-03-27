<?php
/*
 * Plugin Name:       Bootstrap Widgets
 * Description:       Refactoring widgets for bootstrap 4/5. Allows the creation of templates in the theme directory
 * Version:           1.0.0
 * Author:            Norico
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 * Text Domain:       bs-meta
 * Domain Path:       /languages
 */

if( !defined('ABSPATH') ) exit;


function plugin_locate_template($template_name, $template_path = '', $default_path = '' ) {
   // Set variable to search in theme-templates folder of theme.
    if ( ! $template_path ) :
        $template_path = 'widgets/';
    endif;

    // Set default plugin templates path.
    if ( ! $default_path ) :
        $default_path = plugin_dir_path( __FILE__ ) . 'widgets/'; // Path to the template folder
    endif;

    // Search template file in theme folder.
    $template = locate_template( array(
        $template_path . $template_name,
        $template_name
    ) );

    // Get plugins template file.
    if ( ! $template ) :
        $template = $default_path . $template_name;
    endif;

    return apply_filters( 'wcpt_locate_template', $template, $template_name, $template_path, $default_path );

}
function plugin_get_template($template_name, $args = array(), $template_path = '', $default_path = '' ) {

    if ( is_array( $args ) && isset( $args ) ) :
        extract( $args );
    endif;

    $template_file = plugin_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $template_file ) ) :
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
        return;
    endif;

    include $template_file;

}

add_action('widgets_init', function(){
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_BS5');
    unregister_widget('WP_Widget_Recent_Posts');
    register_widget('WP_Widget_Recent_Posts_BS5');
});

require_once( dirname(__FILE__).'/widget-meta.php' );
require_once( dirname(__FILE__).'/widget-recent-posts.php' );


