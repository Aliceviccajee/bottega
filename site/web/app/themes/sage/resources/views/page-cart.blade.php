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

{{'CART'}}
@endsection
