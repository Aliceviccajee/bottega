@php
	$shop_page_id = wc_get_page_id('shop');
	$sold_out = get_field('sold_out', $shop_page_id);
	$paragraph = get_field('paragraph', $shop_page_id);
@endphp
	@if ($sold_out)
	<div class="sold-out">
		<div class="banner"><h4>{{$sold_out}}</h4></div>
		<div class="banner"><p>{{$paragraph}}</p></div>
	</div>
@endif
