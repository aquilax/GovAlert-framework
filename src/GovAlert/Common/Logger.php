<?php

namespace GovAlert\Common;

class Logger
{
	const M_INFO = 'INFO';
	const M_ERROR = 'ERROR';
	const M_DEBUG = 'DEBUG';

	private function log($severity, $message)
	{
		echo '[' . date('c') . '][' . $severity . '] ' . $message . PHP_EOL;
	}

	public function debug($message)
	{
		$this->log(self::M_DEBUG, $message);
	}

	public function info($message)
	{
		$this->log(self::M_INFO, $message);
	}

	public function error($message)
	{
		$this->log(self::M_ERROR, $message);
	}
} 