<?php

class Config
{
	private static $config = [
		'production' => [
			'db' => [
				'host' => 'localhost',
				'user' => 'username',
				'pass' => 'password',
				'name' => 'activist',
				'encoding' => 'utf8',
			],
			'tasksPerRun' => 30,
			'classesBase' => '/_classes',
			'twitterOAuth' => '/www/govalert/twitter/twitteroauth/twitteroauth.php',
			'twitterOAuthConfig' => '/www/govalert/twitter/config.php',
		],
		'test' => [
			'db' => [
				'host' => 'localhost',
				'user' => 'username',
				'pass' => 'password',
				'name' => 'activist',
				'encoding' => 'utf8',
			],
			'tasksPerRun' => 30,
			'classesBase' => '/_classes',
			// TODO: FIXME
			'twitterOAuth' => '/home/aquilax/projects/GovAlert-framework/mocks/twitter/twitteroauth/twitteroauth.php',
			'twitterOAuthConfig' => '/home/aquilax/projects/GovAlert-framework/mocks/twitter/twitteroauth/twitteroauth.php',
		]
	];

	static function env() {
		return getenv('GOV_ENV') == 'test' ? 'test' : 'production';
	}

	/**
	 * Get config key
	 *
	 * @param string $key
	 * @return mixed
	 */
	static function get($key)
	{
		$env = self::env();
		assert(isset(self::$config[$env][$key]), $key . ' is not defined');
		return self::$config[$env][$key];
	}

	/**
	 * Set value to config key
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	static function set($key, $value)
	{
		self::$config[self::env()][$key] = $value;
	}
}