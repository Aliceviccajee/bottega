<div class="product-card">
    <div class="product-image">
				{!!App::create_responsive_image($product->get_image_id()) !!}
    </div>
    <div class="product-details">
        <h4 class="product-title">{{$product->get_title()}}</h4>
        <p class="product-discription">{!! $product->get_short_description() !!}</p>
        <p class="product-price">£{{$product->get_price()}}</p>
				<a href="?add-to-cart={{$product->get_ID()}}" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="{{$product->get_ID()}}" aria-label="Add '{{$product->get_title()}}' to your cart" rel="nofollow">Add to cart</a>
    </div>
</div>