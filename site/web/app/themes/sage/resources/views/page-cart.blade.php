@extends('layouts.app')

@section('content')
@include('partials.page-header')
@php
	do_action('get_header', 'shop');
	do_action( 'woocommerce_before_cart' );
	$quantity = WC()->cart->get_cart_contents_count();
	$sub_total = WC()->cart->get_cart_subtotal();

@endphp

<header class="woocommerce-products-header">
	@if(apply_filters('woocommerce_show_page_title', true))
	<h1 class="woocommerce-products-header__title page-title">{!! woocommerce_page_title(false) !!}</h1>
	@endif

	@php
	do_action('woocommerce_archive_description');
	@endphp
</header>
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
	<div class="product-quantity">Quantity: {{$quantity}}</div>
	<div class="product-subtotal">Total: {!! $sub_total !!}</div>
	<?php do_action( 'woocommerce_before_cart_contents' ); ?>
	@foreach (\WC()->cart->get_cart() as $key => $pizza)
	@php
		$product = apply_filters( 'woocommerce_cart_item_product', $pizza['data'], $pizza, $key );
		@endphp
		{{$product->get_title()}}
		£{{$product->get_price()}}
		x {{$pizza['quantity']}}

	@endforeach
	<div class="actions col-12 col-md-6">
		<div class="update-cart d-md-none">
			<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button>
		</div>

		<?php do_action( 'woocommerce_cart_actions' ); ?>

		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	</div>

@php
	do_action( 'woocommerce_after_cart_table' );
	do_action( 'woocommerce_after_cart_contents' );
	do_action( 'woocommerce_after_cart' );
@endphp
	<footer>
		<a href="{{get_permalink( wc_get_page_id( 'shop' ) )}}">Back to menu</a>
		<a href="{{wc_get_checkout_url()}}">Checkout</a>
	</footer>
@endsection
