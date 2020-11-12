<?php

namespace RestClient\App\Controllers;

use WP_REST_Request;
use Throwable;
use RestClient\App\Models\Booking;

class BookingController {
	private static $booking_slots = [
		"12:00",
		"12:20",
		"12:40",
		"13:00",
		"13:20",
		"13:40",
		"14:00",
		"14:20",
		"14:40",
		"15:00",
		"15:20",
		"15:40",
		"16:00",
		"16:20",
		"16:40",
		"17:00",
		"17:20",
		"17:40",
		"18:00",
		"18:20",
		"18:40",
		"19:00",
		"19:20",
		"19:40",
		"20:00",
		"20:20",
		"20:40",
		"21:00",
		"21:20",
		"21:40",
		"22:00",
		"22:20",
		"22:40",
		"23:00",
		"23:20",
		"23:40",
		"00:00"
	];
	/**
	 * Base Controller constructor.
	 */
	public function __construct($base_route = 'post', $routes = [])
	{
		$this->namespace = 'v1';
		$this->base_route = 'booking';
		$this->response = [];
		$this->register_routes($routes);
	}

	public function get(WP_REST_Request $request)
	{
		try {
			global $wpdb;
			$getParams = $request->get_params();
			$date = isset($getParams['date']) ? $getParams['date'] : date_format(date_create(), 'yy-m-d');

			$bookings = $wpdb->get_results("
				SELECT TIME_FORMAT(time, '%H:%i') as time, COUNT(1) as count
				FROM wp_delivery_slots
				WHERE booking_date BETWEEN '$date' AND '$date'
				GROUP BY time
			");

			$bookings = collect($bookings)->mapWithKeys(function($booking) {
				return [$booking->time => $booking->count];
			})->toArray();

			$this->response = collect(self::$booking_slots)->filter(function($slot) use ($bookings) {
				return !isset($bookings[$slot]) || $bookings[$slot] < 2;
			})->values()->toArray();

			$this->respondWith('json');
		} catch (Throwable $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes($routes = [])
	{
		foreach ($routes as $route) {
			 register_rest_route($this->namespace, $this->base_route . '/' . $route['route'], [
				'methods' => $route['method'],
				'callback' => [$this, $route['callback']],
				'permission_callback' => '__return_true'
			]);

		}
	}

	public function respondWith($type = 'json', $bladeTemplate = '', $optionsGetter = false)
	{
		call_user_func_array([$this, $type], [
			$bladeTemplate,
			$optionsGetter,
		]);
	}

	private function json()
	{
		wp_send_json([
			'data' => $this->response
		]);
	}

	public function distanceCheck(WP_REST_Request $request)
	{

		$getParams = $request->get_params();
		$client_pc = isset($getParams['client_pc']) ? $getParams['client_pc'] : null;
		$base_pc = get_option( "woocommerce_store_postcode" );
		$api_key = env("MAPS_API_KEY");

		$ch = curl_init();

		$url = "https://maps.googleapis.com/maps/api/distancematrix/json";

		// Array of options to be passed to API<br>
		$options = array(
				"origins" => $base_pc,
				"destinations" => $client_pc,
				"units" => "imperial",
				"language" => "en-GB",
				"key" => $api_key
		);
		$request = $url . "?" . http_build_query( $options );

		curl_setopt($ch, CURLOPT_URL, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = json_decode(curl_exec($ch));

		try {
			return collect($output->rows)->reduce(function($response, $row) {
				if (!isset($row->elements[0]->distance)) {
					return [
						'status' => 'invalid',
					];
				}
				$distance = $row->elements[0]->distance;
				return [
					'status' => 'success',
					'miles' => $distance->value * 0.00062137
				];
			 }, []);
		} catch (Exception $e) {
			return [
				'status' => 'fail',
				'miles' => false
			];
		}
	}
}
