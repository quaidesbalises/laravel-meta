<?php

namespace Qdb\Meta;

use Illuminate\Support\Str;

class Meta
{
	/**
	 * Metas attributes values
	 * @var array
	 */
	protected $metas = [];


	/**
	 * Set meta value for the given key
	 *
	 * @param string|array $key
	 * @param string|null  $value
	 */
	public function set($key, $value = '')
	{
		if(!is_array($key)) {
			return $this->metas[$key] = $value;
		}

		foreach($key as $k => $v) {
			$this->set($k, $v);
		}
	}


	/**
	 * Get meta value for the given key
	 *
	 * @param  string $key
	 * @return string
	 */
	public function get($key)
	{
		// if key exists in metas then we return value
		if(isset($this->metas[$key]))
			return $this->metas[$key];

		// if default value exist on config
		if($value = $this->getDefaultConfig($key))
			return $value;

		if($key == 'url')
			return $this->getDefaultUrl();

		if(Str::of($key)->contains(':')) {
			return $this->getDefaultSocial($key);
		}

		return null;
	}


	/**
	 * Get value formatted according to the given key
	 *
	 * @param  string $value
	 * @return string
	 */
	private function getValue($key)
	{
		$value = $this->get($key);

		// image
		if(Str::of($key)->contains('image') && !is_array($value))
			return asset($value);

		return $value;
	}


	/**
	 * Get default value for url key
	 *
	 * @return string
	 */
	private function getDefaultUrl()
	{
		return url()->current();
	}


	/**
	 * Get default value from config for the given key
	 *
	 * @param  string $key
	 * @return string
	 */
	private function getDefaultConfig($key)
	{
		$values = optional(config('meta.default.values'));
		return isset($values[$key]) ? $values[$key] : null;
	}


	/**
	 * Get default social value for the given key
	 *
	 * @param  string $key
	 * @return string
	 */
	private function getDefaultSocial($key)
	{
		// if og:title then we take the value of title
		// if twitter:title then we take the value of og:title
		// og:{attribute} becomes reference for others
		$key = explode(':', $key);
		$prefix = $key[0] != 'og' ? 'og:' : '';
		return $this->get($prefix.$key[1]);
	}


	/**
	 * Get meta type for the given key
	 *
	 * @param  string $key
	 * @return string
	 */
	private function getType($key)
	{
		if(Str::of($key)->contains(['og:', 'twitter:']))
			return 'property';

		return 'name';
	}


	/**
	 * Get keys to display from config or return given keys
	 *
	 * @param  array $keys
	 * @return array
	 */
	public function getDisplayKeys($keys = [])
	{
		if(count($keys)) {
			return $keys;
		}

		return array_unique(array_merge(
			array_keys($this->metas),
			config('meta.default.keys') ?: []
		));
	}


	/**
	 * Print tag for the given key and the given value
	 *
	 * @param  string $key
	 * @return string
	 */
	public function tag($key, $value = '')
	{
		$type = $this->getType($key);
		$value = $value ?: $this->getValue($key);

		if(is_array($value)) {

			foreach($value as $v) {
				$tags[] = $this->tag($key, $v);
			}

			return implode("\n", $tags);
		}

		if($key == 'title') {
			return sprintf('<title>%s</title>', $value);
		}

		return sprintf(
			'<meta %s="%s" content="%s">', 
			$type, $key, $value
		);
	}


	/**
	 * Print all tags for the given meta fields
	 *
	 * @return string
	 */
	public function tags($keys = [])
	{
		if($keys && !is_array($keys)) {
			return $this->tag($keys);
		}

		$tags = [];

		foreach($this->getDisplayKeys($keys) as $key)
		{
			$tags[] = $this->tag($key);
		}

		return implode("\n", $tags);
	}

	
}