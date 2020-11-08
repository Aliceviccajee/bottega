<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	@php wp_head() @endphp
	<script>
		window.LOCALISED_VARS = window.LOCALISED_VARS || {};
		window.LOCALISED_VARS.base_postcode = '{{get_option( "woocommerce_store_postcode" )}}';
		window.LOCALISED_VARS.maps_api_key = '{{env("MAPS_API_KEY")}}';
	</script>
</head>