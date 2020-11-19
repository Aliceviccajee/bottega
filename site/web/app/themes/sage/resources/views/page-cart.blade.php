@extends('layouts.app')

@section('content')

<div class="cart">
	<div class="content">
		<div class="booking-details js-booking-info">
			<p>Your booking details</p>
			<p class="date">Delivery date: </p>
			<p class="time">Delivery time: </p>
		</div>
	</div>
</div>
@php
	the_content();
@endphp
@endsection