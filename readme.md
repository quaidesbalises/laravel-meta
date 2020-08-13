# Laravel meta manager

Manage your meta tags and displays them on your blade layout.  

## Installation


```bash
composer require quaidesbalises/laravel-meta
```


## Configuration

You can override the default configuration. First publish the configuration:

```php
php artisan vendor:publish --provider="Qdb\Meta\MetaServiceProvider" --tag=config
````
This will copy the default config to **config/meta.php** where you can edit it.

The configuration file contains default keys and values array.

```php
'default' => [

/*
* Default keys which needs to be print everytime
* if not exists, it takes the most valuable default value
* g.e : og:title takes the title default value
* g.e : twitter:title takes the og:title default value
*/
'keys' => [
	'title',
	'description',
	'robots',
	'og:title',
	'og:image',
	'og:url',
	'og:description',
	'twitter:card',
	'twitter:title',
	'twitter:description',
	'twitter:image',
	'twitter:url'
],

/**
* Default values for the given keys
*/
'values' => [
	'og:image' => 'images/og-image.jpg',
	'twitter:card' => 'summary_large_image',
]

]

```



## Usage

### Set meta tags

```php
Meta::set('title', 'My title')
Meta::set('description', 'My description')
Meta::set('og:image', '/images/image.jpg')
````

**and** / **or**

```php
Meta::set([
	'title' => 'My title',
	'description' => 'My description',
	'og:image' => '/images/image.jpg'
])
```


### Display meta tags

```
@meta('title') 
@meta('description')
@meta('og:image')
```

**and / or**

```php
@metas(['title', 'description', 'og:image'])
```



The example above print :

```
<title>My title</title>
<meta name="description" content="My description">
<meta property="og:image" content="project.test/images/image.jpg">

```

Also, you can display all metas at once :

```
@metas 
```

Please not that this blade directive will takes care of the **default display keys which are set on your configuration file**. You are free to remove or adding keys to the array as you wants. In case of a specify key was not set, it retrieves the most logic value for you **based on your existing values**.

This is a valuable feature to prevent you to rewrite values for tags like ```og:title```, ```og:description```, ```twitter:title``` etc ...

Given the default keys on the configuration file display above, let's see just below a real world example.


## Example


#### Model :

```php
class Page extends Model
{
	public function setMetas()
	{
		Meta::set([
			'title' => $this->seo_title,
			'description' => $this->seo_desc,
			'robots' => $this->seo_robots
		]);
	}
}
```

#### Controller :

```php
class PagesController extends Controller
{
	public function show(Page $page)
	{
		$page->setMetas();
		return view('pages.page', compact('page'));
	}
}
```

#### Blade view :

```
@metas
```

##### Output :

```
<title>Home page</title>
<meta name="description" content="This is my home page">
<meta name="robots" content="index, nofollow">
<meta property="og:title" content="Home page">
<meta property="og:description" content="This is my home page">
<meta property="og:image" content="http://project.test/images/og-image.jpg">
<meta property="og:url" content="http://project.test">
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="Home page">
<meta property="twitter:description" content="This is my home page">
<meta property="twitter:image" content="http://project.test/images/og-image.jpg">
<meta property="twitter:url" content="http://project.test">
```


Given the display wanted fields which are set on your configuration file and base on available values  ```og:``` and ```twitter:``` are automatically filled.  



## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)