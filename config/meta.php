<?php

return [

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

];