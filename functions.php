<?php

add_action('wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles()
{

    $nectar_theme_version = nectar_get_theme_version();

    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'), $nectar_theme_version);

    if (is_rtl() ) {
           wp_enqueue_style('salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen');
    }
}



add_filter('woocommerce_cart_shipping_method_full_label', 'custom_shipping_method_labels', 10, 2);
function custom_shipping_method_labels( $label, $method )
{
    if($method->method_id == 'local_pickup' ) {
        $label = __(
            "Pick up from Jenny <a class='local-pickup-helptext help-icon' href='#local-pickup-tooltip' title='How can I do this?'></a>
<script>jQuery(document).ready(function() {
  jQuery('.local-pickup-helptext').fancybox({
    maxWidth	: 800,
    maxHeight	: 600,
    fitToView	: false,
    width		: '70%',
    height		: '70%',
    autoSize	: false,
    closeClick	: false,
    openEffect	: 'none',
    closeEffect	: 'none'
  });
});</script>
<div id='local-pickup-tooltip' style='display:none;'><div class='image'></div><div class='copy'>Are you located in or near the Twin Cities? You can pick up your order directly from Miss Jenny in Uptown. Just check \"Local Pickup\" as your shipping option, and then we will email you with some details about where and how you can pick up your order! You can also feel free to <a ahref='/contact-us'>contact us</a> directly with any questions about our location and availability.</div></div>"
        );
    }
    if($method->method_id == 'flat_rate' ) {
        $label = __(
          $method->label . " - <b style='color:red;font-size: 0.85em;'>$".$method->cost."</b><a class='flat-rate-helptext help-icon' href='#local-delivery-tooltip' title='Where do you deliver to?'></a>
<script>jQuery(document).ready(function() {
jQuery('.flat-rate-helptext').fancybox({
  maxWidth	: 800,
  maxHeight	: 600,
  fitToView	: false,
  width		: '70%',
  height		: '70%',
  autoSize	: false,
  closeClick	: false,
  openEffect	: 'none',
  closeEffect	: 'none'
});
});</script>
<div id='local-delivery-tooltip' style='display:none;'><div class='image'></div><div class='copy'>We deliver! If you are located near the Minneapolis metro area, we may deliver to you for a low flat-fee of $".$method->cost.". Same cost no matter how many bottles you order! Delivery zone is based on zip-code. If you would like to arrange a delivery address other than your billing address, just <a ahref='/contact-us'>contact us</a>.</div></div>"
        );
    }
    return $label;
}



// Hook in
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields )
{

     $fields['billing']['billing_country']['label'] = 'We can only ship to the following areas. <a href="/contact-us">Contact Us</a> to inquire about international orders.';
     $fields['shipping']['shipping_country']['label'] = 'We can only ship to the following areas. <a href="/contact-us">Contact Us</a> to inquire about international orders.';
     return $fields;
}




add_filter('woocommerce_before_shipping_calculator', 'shipping_calculator_message');

// Our hooked in function - $fields is passed via the filter!
function shipping_calculator_message(  )
{
    if (!WC()->customer->has_calculated_shipping()) {
         echo "<div class='shipping-calculator-message'>* We will glady ship to your location, wherever you are, but at this time shipping rates can only be calculated for the continental USA. Please <a href='/contact-us'>contact us</a> to arrange an international order.</div>";
    }
}



/********************************************************/
// Adding Dashicons in WordPress Front-end
/********************************************************/
add_action('wp_enqueue_scripts', 'load_dashicons_front_end');
function load_dashicons_front_end()
{
    wp_enqueue_style('dashicons');
}








/*
 * Woocommerce Remove excerpt from single product
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'the_content', 20);




add_filter('woocommerce_product_tabs', 'yikes_remove_description_tab', 20, 1);

function yikes_remove_description_tab( $tabs )
{

    // Remove the description tab
    if (isset($tabs['additional_information']) ) { unset($tabs['additional_information']);
    }
    if (isset($tabs['description']) ) { unset($tabs['description']);
    }
    return $tabs;
}
?>