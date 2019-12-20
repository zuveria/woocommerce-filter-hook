<?php

/****************************************************************************************
		CODE TO MOVE WOOCOMMERCE COMPANY FIELD ON TOP IN CHECKOUT PAGE
****************************************************************************************/
add_filter( 'woocommerce_default_address_fields', 'child_theme_reorder_checkout_fields' );
function child_theme_reorder_checkout_fields( $fields ) {
    $fields['company']['priority'] = 8;
    return $fields;
}

/****************************************************************************************
		IF YOU WANT TO CHANGE THE DEFAULT STATES LIST THAT WAS POPULATED WHEN YOU SELECT
		THE BILLING AND SHIPPING COUNTRY FIELD IN WOOCOMMERCE CHECKOUT FORM USE THIS
		CODE AND CHANGE THE DATA IN ARRAY AS PER THE REQUIREMENT
****************************************************************************************/
add_filter( 'woocommerce_states', 'child_theme_custom_woocommerce_states' );
function child_theme_custom_woocommerce_states( $states ) {
    $states['HK'] = array(
				    'HONG KONG'	=> __( 'Hong Kong Island', 'woocommerce' ),
					'KOWLOON' => __( 'Kowloon', 'woocommerce' ),
					'NEW TERRITORIES' => __( 'New Territories', 'woocommerce' ),
					'Ma Wan' => __( 'Ma Wan', 'woocommerce' )
    );
    return $states;
}

/****************************************************************************************
		CODE TO HIDE SHIPPING WHEN FREE SHIPPING IS AVAILABLE
****************************************************************************************/
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 100 );
function hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}

/****************************************************************************************
 * SHOW COMMENTS FROM OTHER LANGUAGES for WPML
****************************************************************************************/
 
global $sitepress;
remove_filter( 'comments_clauses', array( $sitepress, 'comments_clauses' ), 10, 2 );
add_action('pre_get_comments', function($c){
    $id = [];
    $languages = apply_filters('wpml_active_languages', '');
    if( 1 < count($languages) ){
        foreach( $languages as $l ){
            $id[] = apply_filters( 'wpml_object_id', $c->query_vars['post_id'], 'page', FALSE, $l['code']);
        }
    }
    $c->query_vars['post_id'] = '';
    $c->query_vars['post__in'] = $id;
    return $c;
});
