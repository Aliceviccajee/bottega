<?php

namespace App;
use StoutLogic\AcfBuilder\FieldsBuilder;

acf_add_options_page([
	'page_title' => 'Delivery Settings',
	'menu_title' => 'Delivery Settings',
	'menu_slug'  => 'delivery_settings',
	'capability' => 'edit_theme_options',
	'position'   => '26',
	'autoload'   => true
]);

$fields = new FieldsBuilder('delivery_settings');

$fields
	->setLocation('options_page', '==', 'delivery_settings')
	->addNumber('booking_slots', [
		'label' => 'Max number of orders per slot',
		'required' => 1,
		'default_value' => '3',
		'step' => '1',
	])
	->addNumber('delivery_radius', [
		'label' => 'Delivery Radius in miles',
		'required' => 1,
		'default_value' => '3.8',
		'step' => '0.1',
	]);

return $fields;