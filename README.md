## Intoduction ##

Routebinder is a package designed to help you with clean model validation when using [Laravel route bindings](https://laravel.com/docs/5.8/routing#route-model-binding). Validation is just a series of chained rules, that have to be met, in order for a model to be returned. The process feels very much like chaining an eloquent query.

You can contact me: 
leonczerwinski202@gmail.com

*Make sure to also check out the [API](https://github.com/boring-leon/route-binder/blob/master/API.md) and watch [yotube tutorial](https://youtu.be/p4m0C3remo4)*

## Instalation ##

```bash
composer require leonc/routebinder
```

Once you have required the package, simply include it in **RouteServiceProvider**
```php
use Leonc\RouteBinder\Binder;
```

## Basic usage ##
Let's bind a Post model to our route
```php
Route::bind('post', function($post_id) {
    return Binder::build(\App\Post::class, $post_id)->bind();
});
```

Now ensure that it actually belongs to the authenticated user

```php
Route::bind('auth_post', function($post_id) {
    return Binder::build(\App\Post::class, $post_id)
    ->belongsTo(\App\User::class, auth()->user()->id)
    ->bind();
});
```
Now we'll bind a post which has already been published. Suppose published posts have *is_published* attribute set to *true*

```php
Route::bind('published_post', function($post_id) {
    return Binder::build(\App\Post::class, $post_id)
    ->attrTruthy('is_published')
    ->bind();
});

```

## Error handling ##
### Custom error structure & response type ###

By default, errors are thrown as a JSON response
```php
public function fail($message, $modelName) {
    return response()->json([
        'problem' => $message
    ])->throwResponse();
}
```
We can alter this behavior by providing a custom response strategy. Let's create it
```php
use Leonc\RouteBinder\Strategy\BaseStrategy;

class CustomResponse extends BaseStrategy
{
    public function fail($message, $modelName){
       return redirect()->back()->with('message', $message)->throwResponse();
       //make sure to throw the response instead of just returning it
    }

}
```
Now all we need to do is pass a strategy class string before making an assertion
```php
use App\Strategies\CustomResponse;

Route::bind('auth_post', function($post_id) {
    return Binder::build(\App\Post::class, $post_id)
    ->strategy(CustomResponse::class)
    ->belongsTo(\App\User::class, auth()->user()->id)
    ->bind();
});
```

### Custom error messages ###
We may want to define a custom error message, as default messages are quite verbose and may reveal too much about our system

```php
Route::bind('auth_post', function($post_id) {
    return Binder::build(\App\Post::class, $post_id)
    ->failMessage('Post not authorized')
    ->belongsTo(\App\User::class, auth()->user()->id)
    ->bind();
});
```

### Persisting a custom strategy or an error message throughout the whole assertion chain ###
If you want to apply a strategy or an error message to all assertions inside a chain, just use 
```php
persistFailMessage()
persistStrategy()
```

