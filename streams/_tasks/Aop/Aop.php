<?php

abstract class Aop extends Task {

	protected $sourceId = 12;
	protected $sourceName = 'АОП';

	protected function httpPost($url, $data_url)
	{
		$data_len = strlen($data_url);
		$html = file_get_contents($url, false, stream_context_create(array('http' => array(
			'method' => 'POST',
			'header' => "Content-Length: $data_len\r\n" .
				"Connection: keep-alive\r\n" .
				"Cache-Control: max-age=0\r\n" .
				"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n" .
				"Origin: http://umispublic.minfin.bg\r\n" .
				"Content-Type: application/x-www-form-urlencoded\r\n" .
				"Referer: http://rop3-app1.aop.bg:7778/portal/\r\n" .
				"Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4",
			'timeout' => 5,
			'max_redirects' => 5,
			'content' => $data_url
		))));
		return $html;
	}

	protected function xpathDoc($html, $q)
	{
		if (!$html)
			return array();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);

		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}

} 