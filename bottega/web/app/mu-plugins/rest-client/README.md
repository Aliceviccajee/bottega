# Lunar REST client

Reusable REST client for Roots projects.

# Controllers

## Base Controller
`rest-client/app/Controllers/Controller.php`

On construction a controller registers it's namespace and routes. The base controller provides useful methods to all controllers which inherit from it, for example the PostController.

## Registering a new controller

Should be done in `index.php`, like in the below example. The arguments to the class construct are:

1. REST route
2. Array of acceptable methods that can be used at this endpoint

```php
// Init the endpoints
add_action( 'rest_api_init', function () {
	$Post = new \RestClient\App\Controllers\PostController('post', [
		[
			'method' => 'GET',
			'callback' => 'get', // function that exists on the controller class
		],
		[
			'method' => 'POST',
			'callback' => 'post',
		],
	]);
});
```

Each controller should have it's own file, like in the above example we have `rest-client/app/Controllers/Post.php`.

### Controller internals

#### respondWith
The respondWith function is publicly available and makes use of the `json()` and `view()` internal functions to create and send an appropriate response a to the front-end.

Accepts a type (either json or views) and optional blade template and variables.

```php
public function respondWith($type = 'json', $bladeTemplate = '', $optionsGetter = false)
{
	call_user_func_array([$this, $type], [
		$bladeTemplate,
		$optionsGetter,
	]);
}

public function get(WP_REST_Request $request)
{
	try {
		$this->response = Post::buildQuery()
			->search('hello')
			->orderBy('date', 'DESC')
			->runQuery()
			->posts();

		$this->respondWith('json');
		//Views example
		$this->respondWith('views',
			'patterns.image.image',
			function($post) {
				// Function or method which returns an array of blade variables based on the $post
				return [];
			}
		);
	} catch (Throwable $e) {
		return $e->getMessage();
	}
}
```

## Models

WP_Query interface, designed to make building and executing queries easier. All Models should extend from the `Model.php` (see example `rest-client/app/Models/Post.php`)

The base model is far from complete and should be extended as we work on more projects.

### Query building

The buildQuery static method returns a model instance which can be used to add and modify it's internal WP_Query. This allows chaining of methods to build out the args for a query.

Query builder example:
```php
	$this->response = Post::buildQuery()
				->search('hello')
				->taxonomyFilter([
					'tax-slug-example'
				])
				->orderBy('date', 'DESC');
```
$args look like:
<pre class='xdebug-var-dump' dir='ltr'>
<small>/srv/www/local.lunar-wp-base/current/web/app/themes/sage/vendor/illuminate/support/Debug/Dumper.php:23:</small>
<b>object</b>(<i>RestClient\App\Models\Model</i>)[<i>642</i>]
  <i>public</i> 'args' <font color='#888a85'>=&gt;</font>
    <b>array</b> <i>(size=4)</i>
      'post_type' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'post'</font> <i>(length=4)</i>
      's' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'hello'</font> <i>(length=5)</i>
      'orderby' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'date'</font> <i>(length=4)</i>
      'order' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'DESC'</font> <i>(length=4)</i>
</pre>

Then you can run the query and return a collection of posts
```php
$this->response = Post::buildQuery()
		->search('hello')
		->taxonomyFilter([
			'tax-slug-example'
		])
		->orderBy('date', 'DESC')
		->runQuery()
		->posts();
```
