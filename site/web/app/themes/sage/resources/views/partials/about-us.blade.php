@php
	$shop_page_id = wc_get_page_id('shop');
	$rows = get_field('about_card', $shop_page_id);
@endphp
<div class="about">
	@foreach ($rows as $row)
		<div class="card">
			<div class="card-img">
				{!! wp_get_attachment_image($row['image']) !!}
			</div>
			<div class="card-info">
				<h4>{{$row['title']}}</h4>
				<p>{{$row['paragraph']}}</p>
				<p>{{$row['paragraph_two']}}</p>
			</div>
		</div>
	@endforeach
</div>
