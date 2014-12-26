<?php

namespace GovAlert\Common;

class Logger
{
	const M_INFO = "\033[36mINFO\033[0m";
	const M_ERROR = "\033[31mERROR\033[0m";
	const M_DEBUG = "\033[35mDEBUG\033[0m";

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