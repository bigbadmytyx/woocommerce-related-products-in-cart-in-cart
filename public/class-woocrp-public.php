<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com/
 * @since      1.0.0
 *
 * @package    Woocrp
 * @subpackage Woocrp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocrp
 * @subpackage Woocrp/public
 * @author     Dmitry Lebedko <dmitrylebedko@gmail.com>
 */
class Woocrp_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->add_shortcode();

	}

	/**	
	 * Render html markup
	 * 
	 */
	public function display_related_products() {
		
		$related_products_ids = [];
		$related_products_ids = $this->get_related_products_ids();
		if (empty($related_products_ids)) $related_products_ids = $this->get_most_purchased_products_ids();
 
		$args = array(
			'posts_per_page' => 5,
			'post__in' => $related_products_ids,
			'post_type' => 'product',
		);
		$related_products = new WP_Query( $args );
	 
		if ( $related_products->have_posts() ) {
			require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/woocrp-public-display.php';
		}

	}

	/**
	 * Return html for shortcode
	 * 
	 */
	public function return_related_products() {
		ob_start();
		
		$this->display_related_products();
		
		$html = ob_get_clean();
		
		return $html;
	}

	/**
	 * Get array of related products ids 
	 * 
	 */
	private function get_related_products_ids() {

		$current_user_id = get_current_user_id();
		$cart_ids = $this->get_cart_products_ids();
		$related_products_ids = [];

		// Get all orders by UID
		$args = ['status' => wc_get_is_paid_statuses(), 'type' => 'shop_order', 'limit' => -1, 'customer' => $current_user_id];
		$orders = wc_get_orders($args);

		if ($orders) {
			foreach ($orders as $order) {
				$has_related_items = false;
				$items = $order->get_items();
				$items_ids = [];
				foreach ($items as $k => $item) {
					$items_ids[$k] = $item->get_product_id();
				 	if (in_array($items_ids[$k], $cart_ids)) {
						unset($items_ids[$k]); // Remove product itself
						$has_related_items = true;
					}
				}
				if ($has_related_items) $related_products_ids = array_merge($related_products_ids, $items_ids);
			}
		}
		return $related_products_ids;
	}

	/**
	 * Get products by total_sales meta value
	 * 
	 */
	private function get_most_purchased_products_ids($limit = 5) {	 
		return wc_get_products([
			'status' => 'publish',
			'exclude' => $this->get_cart_products_ids(),
			'limit' => $limit,
			'orderby' => 'meta_value_num',
			'order' => 'DESC', 
        	'meta_key'  => 'total_sales',
			'return' => 'ids'
		]);
	}

	/**
	 * Get cart items ids
	 * 
	 */
	private function get_cart_products_ids() {
		$cart_ids = [];
		if (WC()->cart !== null) {
			foreach (WC()->cart->get_cart() as $cart_item) {
				$cart_ids[] = $cart_item['data']->get_id();
			}
		}
		return $cart_ids;
	}
	
	private function add_shortcode() {
		add_shortcode('woocrp_products', [$this, 'return_related_products']);
	}

}
