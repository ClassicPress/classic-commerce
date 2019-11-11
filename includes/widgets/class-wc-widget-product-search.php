<?php
/**
 * Product Search Widget.
 *
 * @package ClassicCommerce/Widgets
 * @version WC-2.3.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget product search class.
 */
class WC_Widget_Product_Search extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_product_search';
		$this->widget_description = __( 'A search form for your store.', 'classic-commerce' );
		$this->widget_id          = 'woocommerce_product_search';
		$this->widget_name        = __( 'Product Search', 'classic-commerce' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title', 'classic-commerce' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$this->widget_start( $args, $instance );

		get_product_search_form();

		$this->widget_end( $args );
	}
}
