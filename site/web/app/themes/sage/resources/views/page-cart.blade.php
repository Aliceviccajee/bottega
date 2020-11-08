@extends('layouts.app')

@section('content')

<div class="js-booking-info">
	<p>Your booking information</p>
	<p class="date">Delivery date: </p>
	<p class="time">Delivery time: </p>
</div>
@php
	the_content();
@endphp
@endsection