<?php

/**
* woocommerce_package_rates is a 2.1+ hook
*/
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2 );
 
/**
 * Hide shipping rates when free shipping is available
 *
 * @param array $rates Array of rates found for the package
 * @param array $package The package array/object being shipped
 * @return array of modified rates
 */
function hide_shipping_when_free_is_available( $rates, $package ) {
 	
 	// Only modify rates if free_shipping is present
  	if ( isset( $rates['free_shipping'] ) ) {
  	
  		// To unset a single rate/method, do the following. This example unsets flat_rate shipping
  		//unset( $rates['flat_rate'] );
  		
  		// To unset all methods except for free_shipping, do the following
  		$free_shipping          = $rates['free_shipping'];
  		$rates                  = array();
  		$rates['free_shipping'] = $free_shipping;
	}
	
	return $rates;
}

/**  
* Output items for display  
*/  
function woocommerce_pip_custom_order_items_table( $order, $show_price = FALSE ) {  

	$return = '';  
	
	foreach($order->get_items() as $item) {  
		$_product = $order->get_product_from_item( $item );
		$order_id = trim(str_replace('#', '', $order->get_order_number())); 
		$product_id = $_product->id;  
		$sku = $variation = '';  
		$sku = $_product->get_sku();
		$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
		$variation = '<br/><small>' . $item_meta->display( TRUE, TRUE ) . '</small>';
		if(strpos($variation, 'Beans') == FALSE) {
			$variation = '<br/><small><span style="color: red;">' . $item_meta->display( TRUE, TRUE ) . '<span></small>';	
		}
		$qty_display = $item['qty'];
		if($item['qty'] > 1) {
			$qty_display = '<span style="color: red;">'.$item['qty'].'</span>';
		}  
		$product_weight = ($_product->get_weight()) ? ($_product->get_weight() * $item['qty']) . ' ' . get_option('woocommerce_weight_unit') : __( 'n/a', 'woocommerce-pip' );
		if($product_weight != '1 lbs' && $product_weight != 'n/a') {
			$product_weight = '<span style="color: red;">'.$product_weight.'</span>';
		}
		
		$subscription_key = WC_Subscriptions_Manager::get_subscription_key( $order_id, $product_id );
		$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
		
		$first_shipment_date = '';
		
		if($subscription['start_date'] != 0) {
			$first_shipment_date = ' - First Shipment on ' . date('M d, Y', strtotime($subscription['start_date'] . ' first day of next month'));
		}
		  
		$return .= '<tr>  
		<td style="text-align:left; padding: 3px;">' . $sku . '</td>  
		<td style="text-align:left; padding: 3px;">' . apply_filters('woocommerce\_order_product_title', $item['name'], $_product) . $first_shipment_date . $variation . '</td>  
		<td style="text-align:left; padding: 3px;">'.$qty_display.'</td>';  
		if ($show_price) {  
			$return .= '<td style="text-align:left; padding: 3px;">';  
			if ( $order->display_cart_ex_tax || !$order->prices_include_tax ) :  
				$ex_tax_label = ( $order->prices_include_tax ) ? 1 : 0;  
				$return .= woocommerce_price( $order->get_line_subtotal( $item ), array('ex_tax_label' => $ex_tax_label ));  
			else :  
				$return .= woocommerce_price( $order->get_line_subtotal( $item, TRUE ) );  
			endif;  
			$return .= '</td>';  
		} else {  
			$return .= '<td style="text-align:left; padding: 3px;">';  
			$return .= $product_weight;  
			$return .= '</td>';  
		}  
		$return .= '</tr>';  
	}  
	$return = apply_filters( 'woocommerce_pip_order_items_table', $return );
	  
	return $return;

}