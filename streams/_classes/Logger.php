<?php

class Logger {

	const M_INFO = 'INFO';
	const M_ERROR = 'ERROR';

	private function log($severity, $message) {
		echo '['. date('c') . ']['. $severity. '] ' . $message . PHP_EOL;
	}

	public function info($message) {
		$this->log(self::M_INFO, $message);
	}

	public function error($message) {
		$this->log(self::M_ERROR, $message);
	}
} 