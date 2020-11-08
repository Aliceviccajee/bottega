<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });

    /**
     * Add additional image sizes
     */
    add_image_size( 'small', 300, 9999 ); // 300px wide unlimited height
    add_image_size( 'medium-small', 450, 9999 ); // 300px wide unlimited height
    add_image_size( 'xl', 1200, 9999 ); // 1200px wide unlimited height
    add_image_size( 'xxl', 2000, 9999 ); // 2000px wide unlimited height
    add_image_size( 'xxxl', 3000, 9999 ); // 3000px wide unlimited height
    add_image_size( 'portfolio_full', 9999, 900 ); // 900px tall unlimited width
});

/**
 * Initialize ACF Builder
 */
add_action('init', function () {
    collect(glob(config('theme.dir').'/app/fields/*.php'))->map(function ($field) {
        return require_once($field);
    })->map(function ($field) {
        if ($field instanceof FieldsBuilder) {
            if(function_exists('acf_add_local_field_group')) {
                acf_add_local_field_group($field->build());
            };
        }
    });
});

add_action( 'woocommerce_proceed_to_checkout', function() {
 $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

 echo ' <a href="'.$shop_page_url.'" class="button">Back to menu</a>';
});

add_action( 'woocommerce_order_status_completed', function ( $order_id ) {

}, 10, 1 );

// Outputting the hidden field in checkout page
add_action( 'woocommerce_after_order_notes', function( $checkout ) {
    echo '<input type="hidden" class="input-hidden" name="date_slot" id="date_slot" /><input type="hidden" class="input-hidden" name="time_slot" id="time_slot" />';
});

// Saving the hidden field value in the order metadata
add_action( 'woocommerce_checkout_update_order_meta', function( $order_id ) {
    if ( ! empty( $_POST['date_slot'] ) ) {
        update_post_meta( $order_id, '_date_slot', sanitize_text_field( $_POST['date_slot'] ) );
    }
    if ( ! empty( $_POST['time_slot'] ) ) {
        update_post_meta( $order_id, '_time_slot', sanitize_text_field( $_POST['time_slot'] ) );
    }
});

// Displaying "Verification ID" in customer order
add_action( 'woocommerce_order_details_after_customer_details', function( $order ) {
	// compatibility with WC +3
	$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

	echo '<p class="order-time"><strong>'.__('Date', 'woocommerce') . ':</strong> ' . get_post_meta( $order_id, '_date_slot', true ) .'</p>';
	echo '<p class="order-time"><strong>'.__('Time', 'woocommerce') . ':</strong> ' . get_post_meta( $order_id, '_time_slot', true ) .'</p>';
}, 10 );


 // Display "Date" on Admin order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', function( $order ) {
	// compatibility with WC +3
	$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
	echo '<p><strong>'.__('Date', 'woocommerce').':</strong> ' . get_post_meta( $order_id, '_date_slot', true ) . '</p>';
	echo '<p><strong>'.__('Time', 'woocommerce').':</strong> ' . get_post_meta( $order_id, '_time_slot', true ) . '</p>';
}, 10, 1 );


// Displaying "Date" on email notifications
add_action('woocommerce_email_customer_details', function( $order, $sent_to_admin, $plain_text, $email ) {
	// compatibility with WC +3
	$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

	$output = '';
	$date_slot = get_post_meta( $order_id, '_date_slot', true );

	if ( !empty($date_slot) )
			$output .= '<div><strong>' . __( "Date:", "woocommerce" ) . '</strong> <span class="text">' . $date_slot . '</span></div>';
	if ( !empty($time_slot) )
			$output .= '<div><strong>' . __( "Time:", "woocommerce" ) . '</strong> <span class="text">' . $time_slot . '</span></div>';

	echo $output;
}, 15, 4 );

add_action( 'woocommerce_order_status_changed', function( $order_id, $old_status, $new_status ){
	if( $new_status == "completed" ) {
		$date = get_post_meta( $order_id, '_date_slot', true );
		$time = get_post_meta( $order_id, '_time_slot', true );

		global $wpdb;

		$wpdb->query("INSERT INTO wp_delivery_slots (time, booking_date) VALUES ('$time', '$date');");
	}
}, 99, 3 );
