@php
$query = new WC_Product_Query( array(
    'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => -1,
    'return' => 'objects',
) );
$products = $query->get_products();
@endphp

@extends('layouts.app')

@section('content')
@include('partials.page-header')
@include('partials.about-us')
@include('partials.booking-form')

<div class="menu">
	@php
		do_action('get_header', 'shop');
		do_action('woocommerce_before_main_content');
		the_content();
	@endphp

	<header class="woocommerce-products-header">
		@if(apply_filters('woocommerce_show_page_title', true))
		@endif

		@php
			do_action('woocommerce_archive_description');
		@endphp
	</header>

		<div id="menu" class="menu">
		<div class="products">
			<h1>Menu</h1>
			@foreach ($products as $product)
				@include('partials.product', ['product' => $product])
			@endforeach
		</div>

		@php
			do_action('woocommerce_after_main_content');
		@endphp
	</div>
</div>
@endsection
