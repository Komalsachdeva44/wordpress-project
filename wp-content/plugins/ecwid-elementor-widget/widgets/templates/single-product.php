<?php
if (!defined('ABSPATH')) exit;

$pid = get_query_var('ecwid_product_id');
$store_id = get_option('ecwid_store_id'); // You can store this in WP options or pass differently
$api_token = get_option('ecwid_api_token');

// Basic fallback
if (!$pid || !$store_id || !$api_token) {
    echo '<p>Product not found or store not configured.</p>';
    return;
}

$response = wp_remote_get("https://app.ecwid.com/api/v3/{$store_id}/products/{$pid}", [
    'headers' => ['Authorization' => 'Bearer ' . $api_token]
]);

if (is_wp_error($response)) {
    echo '<p>Error fetching product.</p>';
    return;
}

$product = json_decode(wp_remote_retrieve_body($response), true);
if (empty($product['id'])) {
    echo '<p>Product not found.</p>';
    return;
}
?>

<div class="ecwid-product-detail" style="max-width:800px;margin:auto;padding:40px;">
    <h1><?php echo esc_html($product['name']); ?></h1>
    <img src="<?php echo esc_url($product['imageUrl']); ?>" style="width:100%;max-width:400px;">
    <p><strong>Price:</strong> £<?php echo esc_html($product['price']); ?></p>
    <div><?php echo wp_kses_post($product['description']); ?></div>
    <a href="<?php echo esc_url(home_url()); ?>" style="display:inline-block;margin-top:20px;">← Back to Products</a>
</div>
