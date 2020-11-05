@php 
$query = new WC_Product_Query( array(
    'orderby' => 'date',
    'order' => 'DESC',
    'return' => 'objects',
) );
$products = $query->get_products();
@endphp

@extends('layouts.app')

@section('content')
@include('partials.page-header')
@php
	do_action('get_header', 'shop');
	do_action('woocommerce_before_main_content');
@endphp

<header class="woocommerce-products-header">
	@if(apply_filters('woocommerce_show_page_title', true))
	<h1 class="woocommerce-products-header__title page-title">{!! woocommerce_page_title(false) !!}</h1>
	@endif
	
	@php
		do_action('woocommerce_archive_description');
		@endphp
	</header>
	
	<div class="menu">
	<div class="products">
		@if(woocommerce_product_loop())
			@foreach ($products as $product)
			<div class="product-card">
				<div class="product-image">
					{!!$product->get_image()!!}
				</div>
				<div class="product-details">
					<h4 class="product-title">{{$product->get_title()}}</h4>
					<p class="product-discription">{{$product->get_short_description()}}</p>
				</div>
			</div>
				@endforeach
				@else
				@php
				do_action('woocommerce_no_products_found');
			@endphp
		@endif
	</div>

	@php
		do_action('woocommerce_after_main_content');
		do_action('get_sidebar', 'shop');
		do_action('get_footer', 'shop');
	@endphp
	@endsection
</div>
