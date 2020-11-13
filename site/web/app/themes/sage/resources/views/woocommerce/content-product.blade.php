@php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
@endphp
<li class="product-card">
	@php do_action( 'woocommerce_before_shop_loop_item' ); @endphp
	<div class="product-image">
		{!!$product->get_image()!!}
	</div>
	<div class="product-details">
			<h4 class="product-title">{{$product->get_title()}}</h4>
			<p class="product-discription">{{$product->get_short_description()}}</p>
	</div>
	<a href="?add-to-cart={{$product->get_ID()}}" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="{{$product->get_ID()}}" aria-label="Add '{{$product->get_title()}}' to your cart" rel="nofollow">Add to cart</a>
	@php
	do_action( 'woocommerce_after_shop_loop_item' );
	@endphp
</li>
