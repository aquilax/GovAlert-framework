<?php

class Config {

	private static $config = [
		'db'=> [
			'host' => 'localhost',
			'user' => 'username',
			'pass' => 'password',
			'name' => 'activist',
			'encoding' => 'utf8',
		],
		'classesBase' => '/_classes'
	];

	/**
	 * Get config key
	 *
	 * @param string $key
	 * @return mixed
	 */
	static function get($key) {
		assert(isset(self::$config[$key]), $key . ' is not defined');
		return self::$config[$key];
	}

	/**
	 * Set value to config key
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	static function set($key, $value) {
		self::$config[$key] = $value;
	}
}