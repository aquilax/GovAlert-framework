<?php

class TwitterOAuth
{

	static function post()
	{
		echo '[' . date('c') . '][MOCK] Called ' . __METHOD__ . ' with ' . json_encode(func_get_args()) . PHP_EOL;
		$res = new stdClass();
		$res->code = null;
		$res->errors = null;
		$res->id_str = '';
		return $res;
	}

	function get()
	{
		return [];
	}
}