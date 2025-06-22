<?php
namespace Komal\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if (!defined('ABSPATH')) exit;

class Ecwid_Products_Widget extends Widget_Base {
    public function get_name(){ return 'ecwid_komal_products'; }
    public function get_title(){ return 'Ecwid Products Komal'; }
    public function get_icon(){ return 'eicon-paint-brush'; }
    public function get_categories(){ return ['ecwid-products']; }

    protected function register_controls(){
        $this->start_controls_section('ecwid_settings', ['label'=>__('Ecwid Settings','plugin-name')]);

        $this->add_control('show_sidebar', [
            'label'=>__('Show Sidebar?','plugin-name'),
            'type'=>Controls_Manager::SELECT,
            'options'=>['yes'=>'Yes','no'=>'No'],
            'default'=>'yes'
        ]);

        $this->add_control('store_id', [
            'label'=>__('Ecwid Store ID','plugin-name'),
            'type'=>Controls_Manager::TEXT
        ]);

        $this->add_control('api_token', [
            'label'=>__('Ecwid API Token','plugin-name'),
            'type'=>Controls_Manager::TEXT
        ]);

        $this->end_controls_section();
    }

    public function render(){
        $s = $this->get_settings_for_display();
        if(empty($s['store_id']) || empty($s['api_token'])){
            echo '<p>Please set Store ID & API Token.</p>'; return;
        }

        $prods = $this->fetch_all_products($s['store_id'], $s['api_token']);
        $cats  = $this->fetch_all_categories($s['store_id'], $s['api_token']);

        if(empty($prods)){ echo '<p>No products.</p>'; return; }

        $catTree = $this->build_cat_tree($cats);
        $filtered_categories = $this->filter_empty_categories($catTree, $prods);

        // In your render() method, update the HTML structure to include these classes:
echo '<div class="ecwid-products-wrapper">';

// Sidebar
if($s['show_sidebar']==='yes'){
    echo '<aside class="ecwid-sidebar">';
    echo '<h3>Categories</h3><form id="category-filter">';
    echo '<div class="category-option"><label><input type="radio" name="category" value="0" checked> <span>All Products</span></label></div>';
    $this->render_category_radio($filtered_categories);
    echo '</form></aside>';
}

// Products section
echo '<div class="ecwid-main">';
echo '<div class="view-controls"><button id="toggle-view" class="view-toggle-btn">Switch to List View</button></div>';
echo '<div id="ecwid-products" class="card-view"></div>';
echo '<div id="load-more-wrap"><button id="load-more" class="load-more-btn">Load More</button></div>';
echo '</div></div>';
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productsData = <?php echo json_encode($prods); ?>;
            const container = document.getElementById('ecwid-products');
            const loadMoreBtn = document.getElementById('load-more');
            const toggleBtn = document.getElementById('toggle-view');
            const radios = document.querySelectorAll('#category-filter input[type="radio"]');

            let viewMode = 'card';
            let visibleCount = 0;
            const step = 10;

            function renderProduct(product) {
                const categoryIds = (product.categories || []).map(c => typeof c === 'object' ? c.id : c).join(',');
                const wrapper = document.createElement('div');
                wrapper.className = `product-card ${viewMode}-view`;
                wrapper.setAttribute('data-category', categoryIds);
                wrapper.style.border = '1px solid #ccc';
                wrapper.style.padding = '10px';
                wrapper.style.width = viewMode === 'card' ? '200px' : '100%';
                wrapper.style.display = 'block';

                wrapper.innerHTML = `
                    <div style="display:${viewMode === 'list' ? 'flex' : 'block'}; gap:15px;">
                        <img src="${product.thumbnailUrl || ''}" style="width:${viewMode === 'list' ? '100px' : '100%'}; height:auto;">
                        <div>
                            <h4>${product.name || ''}</h4>
                            <p>£${product.price || ''}</p>
<a href="${product.url}" class="view-details-btn" target="_blank">View Details</a>

                                </div>
                    </div>
                `;
                return wrapper;
            }

            function renderProducts(){
                const selectedCat = document.querySelector('#category-filter input[type="radio"]:checked')?.value || '0';
                let displayed = 0;
                container.innerHTML = '';
                visibleCount = 0;

                for(const prod of productsData){
                    const catIds = (prod.categories || []).map(c => typeof c === 'object' ? c.id : c).map(String);
                    const match = selectedCat === '0' || catIds.includes(selectedCat);

                    if(match){
                        if(displayed < step){
                            container.appendChild(renderProduct(prod));
                            visibleCount++;
                        }
                        displayed++;
                    }
                }

                if(visibleCount < displayed){
                    loadMoreBtn.style.display = 'inline-block';
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            }

            function loadMore(){
                const selectedCat = document.querySelector('#category-filter input[type="radio"]:checked')?.value || '0';
                let count = 0;
                for(const prod of productsData){
                    const catIds = (prod.categories || []).map(c => typeof c === 'object' ? c.id : c).map(String);
                    const match = selectedCat === '0' || catIds.includes(selectedCat);
                    if(match && count >= visibleCount && count < visibleCount + step){
                        container.appendChild(renderProduct(prod));
                    }
                    if(match) count++;
                }
                visibleCount += step;
                if(visibleCount >= count){
                    loadMoreBtn.style.display = 'none';
                }
            }

            function toggleView(){
                viewMode = viewMode === 'card' ? 'list' : 'card';
                toggleBtn.textContent = viewMode === 'card' ? 'Switch to List View' : 'Switch to Card View';
                renderProducts();
            }

            radios.forEach(r => r.addEventListener('change', renderProducts));
            loadMoreBtn.addEventListener('click', loadMore);
            toggleBtn.addEventListener('click', toggleView);

            renderProducts();
        });
        </script>
        <style>
            .card-view .product-card { display: block; }
            .list-view .product-card { display: block; width: 100% !important; }
        </style>
        <?php
    }

    private function fetch_all_products($sid, $tok){
        $r = wp_remote_get("https://app.ecwid.com/api/v3/{$sid}/products?limit=900", ['headers'=>['Authorization'=>'Bearer '.$tok]]);
        if(is_wp_error($r)) return [];
        return json_decode(wp_remote_retrieve_body($r), true)['items'] ?? [];
    }

    private function fetch_all_categories($sid, $tok){
        $r = wp_remote_get("https://app.ecwid.com/api/v3/{$sid}/categories", ['headers'=>['Authorization'=>'Bearer '.$tok]]);
        if(is_wp_error($r)) return [];
        return json_decode(wp_remote_retrieve_body($r), true)['items'] ?? [];
    }

    private function build_cat_tree($cats, $pid = 0){
        $out = [];
        foreach($cats as $c) if(($c['parentId'] ?? 0) == $pid){
            $c['children'] = $this->build_cat_tree($cats, $c['id']);
            $out[] = $c;
        }
        return $out;
    }

    private function filter_empty_categories($categories, $products){
        $filtered = [];
        foreach($categories as $category){
            $product_count = 0;
            $filtered_children = [];

            foreach($products as $product){
                if(!empty($product['categories'])){
                    foreach($product['categories'] as $cid){
                        $cat_id = is_array($cid) ? ($cid['id'] ?? null) : $cid;
                        if($cat_id == $category['id']){
                            $product_count++;
                            break;
                        }
                    }
                }
            }

            if(!empty($category['children'])){
                $category['children'] = $this->filter_empty_categories($category['children'], $products);
                foreach($category['children'] as $child){
                    $product_count += $child['product_count'] ?? 0;
                }
            }

            if($product_count > 0){
                $category['product_count'] = $product_count;
                $filtered[] = $category;
            }
        }
        return $filtered;
    }
public function get_style_depends() {
    return ['ecwid-products-style'];
}

    private function render_category_radio($categories, $level = 0){
        foreach($categories as $category){
            echo '<div style="padding-left:'.($level*20).'px">';
            echo '<label>';
            echo '<input type="radio" name="category" value="'.esc_attr($category['id']).'"> ';
            echo esc_html($category['name']);
            if(isset($category['product_count'])){
                echo ' ('.intval($category['product_count']).')';
            }
            echo '</label>';
            if(!empty($category['children'])){
                $this->render_category_radio($category['children'], $level + 1);
            }
            echo '</div>';
        }
    }
}
