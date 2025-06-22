<?php
/**
 * Plugin Name: Ecwid Elementor Widget
 * Description: Custom Elementor widget to display Ecwid products with category filters and internal product detail view.
 * Version: 1.1
 * Author: Komal
 */

if (!defined('ABSPATH')) exit;

// Register widget scripts & styles
function ecwid_elementor_widget_assets() {
    wp_register_style(
        'ecwid-products-style',
        plugin_dir_url(__FILE__) . 'widgets/assets/css/style.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/assets/css/style.css')
    );
}
add_action('wp_enqueue_scripts', 'ecwid_elementor_widget_assets');

// Register Elementor widget
function ecwid_register_elementor_widgets($widgets_manager) {
    require_once plugin_dir_path(__FILE__) . 'widgets/class-ecwid-products-widget.php';
    require_once plugin_dir_path(__FILE__) . 'widgets/class-ecwid-product-detail.php';
    $widgets_manager->register(new \Komal\Widgets\Ecwid_Products_Widget());
    $widgets_manager->register(new \Komal\Widgets\Ecwid_Product_Detail());
}
add_action('elementor/widgets/register', 'ecwid_register_elementor_widgets');

// Add rewrite rule for product detail
function ecwid_add_rewrite_rule() {
    add_rewrite_rule('^product/([0-9]+)/?', 'index.php?ecwid_product_id=$matches[1]', 'top');
}
add_action('init', 'ecwid_add_rewrite_rule');

// Add custom query var
function ecwid_add_query_vars($vars) {
    $vars[] = 'ecwid_product_id';
    return $vars;
}
add_filter('query_vars', 'ecwid_add_query_vars');

// Load template for product detail
function ecwid_template_redirect() {
    $product_id = get_query_var('ecwid_product_id');
    if ($product_id) {
        include plugin_dir_path(__FILE__) . 'widgets/templates/single-product.php';
        exit;
    }
}
add_action('template_redirect', 'ecwid_template_redirect');