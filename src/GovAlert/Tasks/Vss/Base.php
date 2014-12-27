<?php

/*

0: дневен ред http://www.vss.justice.bg/bg/schedule/1.htm
1: протоколи http://www.vss.justice.bg/bg/decisions/2014/1.htm
2: новини http://www.vss.justice.bg/bg/press/2014/2014.htm

*/

namespace GovAlert\Tasks\Vss;

abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 9;
	protected $sourceName = 'ВСС';
	protected $categoryURLName = '';

	public function __construct(\GovAlert\Common\Database $db,
								\GovAlert\Common\Logger $logger,
								\GovAlert\Common\Loader $loader,
								\GovAlert\Common\Processor $processor,
								$debug = false)
	{
		parent::__construct($db, $logger, $loader, $processor, $debug);
		$this->categoryURL = $this->getLink($this->categoryURLName);
	}

	function getLink($which)
	{
		$html = $this->loadURL("http://www.vss.justice.bg/bg/sessions.htm");
		$html = mb_convert_encoding($html, 'UTF-8', 'cp1251');
		$pos = mb_strpos($html, $which . "|") + mb_strlen($which) + 1;
		return "http://www.vss.justice.bg/bg/" . mb_substr($html, $pos, mb_strpos($html, "\"", $pos) - $pos);
	}

	function xpath($html)
	{
		if (!$html)
			return null;
		$html = mb_convert_encoding($html, 'UTF-8', 'cp1251');
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
		$doc = new \DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new \DOMXpath($doc);
	}

	function xpathDoc($html, $q)
	{
		$xpath = $this->xpath($html);
		if ($xpath == null)
			return array();
		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}
}