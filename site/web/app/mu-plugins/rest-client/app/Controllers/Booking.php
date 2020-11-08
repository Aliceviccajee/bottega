<?php

namespace RestClient\App\Controllers;

use WP_REST_Request;
use Throwable;
use RestClient\App\Models\Booking;

class BookingController {
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

			$this->response = $wpdb->get_results("
				SELECT *
				FROM wp_delivery_slots
				WHERE booking_date BETWEEN '$date' AND '$date'
			");

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
}
