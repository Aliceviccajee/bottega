@extends('layouts.app')

@section('content')

<div class="js-booking-info">
	<p>Your booking information</p>
	<p class="date">Date: </p>
	<p class="time">Time: </p>
</div>
@php
	the_content();
@endphp
@endsection