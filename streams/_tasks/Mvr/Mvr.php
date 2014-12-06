<?php

/*
Links
0: новини http://press.mvr.bg/default.htm
1: кампании http://press.mvr.bg/Kampanii/default.htm
2: благоевград http://www.blagoevgrad.mvr.bg/Prescentar/Novini/default.htm
3: благоевград издирвани http://www.blagoevgrad.mvr.bg/Prescentar/Izdirvani_lica/default.htm
4: бургас ------ http://www.rdvr-burgas.org/Bul/Suobshtenie/Realno.htm
5: варна http://varna.mvr.bg/Prescentar/Novini/default.htm
6: велико търново http://www.veliko-tarnovo.mvr.bg/Prescentar/Novini/default.htm
7: велико търново изчезнали http://www.veliko-tarnovo.mvr.bg/Prescentar/Izdirvani_lica/
8: видин http://www.vidin.mvr.bg/PressOffice/News/default.htm
9: видин изчезнали http://www.vidin.mvr.bg/Pressoffice/Izdirvani_lica/default.htm
10: враца http://www.vratza.mvr.bg/PressOffice/News/default.htm
11: враца изчезнали http://www.vratza.mvr.bg/Pressoffice/Izdirvani_lica/default.htm
12: габрово http://www.gabrovo.mvr.bg/PressOffice/News/default.htm
13: габрово изчезнали http://www.gabrovo.mvr.bg/PressOffice/Wanted/default.htm
14: добрич http://dobrich.mvr.bg/Prescentar/Novini/default.htm
15: кърджали http://www.kardjali.mvr.bg/PressOffice/News/default.htm
16: кърджали изчезнали http://www.kardjali.mvr.bg/PressOffice/Izirva_se/default.htm
17: кюстендил http://www.kustendil.mvr.bg/PressOffice/News/default.htm
18: ловеч http://www.lovech.mvr.bg/PressOffice/News/default.htm
19: ловеч изчезнали http://www.lovech.mvr.bg/PressOffice/Wanted/default.htm
20: монтана http://www.montana.mvr.bg/PressOffice/News/default.htm
21: монтана изчезнали http://www.montana.mvr.bg/PressOffice/Wanted/default.htm
22: пазарджик http://pazardjik.mvr.bg/Prescentar/Novini/default.htm
23: пазарджик изчезнали http://pazardjik.mvr.bg/Prescentar/Izdirvani_lica/default.htm
24: перник http://www.pernik.mvr.bg/Prescentar/Novini/default.htm
25: перник изчезнали http://www.pernik.mvr.bg/Prescentar/Izdirvani_lica/default.htm
26: плевен http://www.pleven.mvr.bg/PressOffice/News/default.htm
27: плевен изчезнали http://www.pleven.mvr.bg/PressOffice/Wanted/default.htm
28: пловдив ------ http://plovdiv.mvr.bg/news.php
29: разград http://www.razgrad.mvr.bg/PressOffice/News/default.htm
30: русе http://www.ruse.mvr.bg/Prescentar/Novini/default.htm
31: русе изчезнали http://www.ruse.mvr.bg/Prescentar/Izdirvani_lica/default.htm
32: силистра http://www.silistra.mvr.bg/Prescentar/Novini/default.htm
33: силистра изчезнали http://www.silistra.mvr.bg/Prescentar/Izdirvani_lica/default.htm
34: сливен http://sliven.mvr.bg/Prescentar/Novini/default.htm
35: сливен изчезнали http://sliven.mvr.bg/Prescentar/Izdirvani_lica/default.htm
36: смолян http://www.smolyan.mvr.bg/Prescentar/Novini/default.htm
37: смолян изчезнали http://www.smolyan.mvr.bg/Prescentar/Izdirvani_lica/default.htm
38: софия http://www.odmvr-sofia.mvr.bg/Prescentar/Novini/default.htm
39: стара загора http://www.starazagora.mvr.bg/PressOffice/News/default.htm
40: стара загора изчезнали http://www.starazagora.mvr.bg/PressOffice/Wanted/default.htm
41: търговище http://targovishte.mvr.bg/Prescentar/Novini/default.htm
42: хасково http://haskovo.mvr.bg/Prescentar/Novini/default.htm
43: шумен http://www.shumen.mvr.bg/Prescentar/Novini/default.htm
44: шумен изчезнали http://www.shumen.mvr.bg/Prescentar/Izdirvani_lica/default.htm
45: ямбол http://www.yambol.mvr.bg/Prescentar/Novini/default.htm
46: ямбол изчезнали http://www.yambol.mvr.bg/Izdirvani_lica/default.htm
*/

class Mvr extends Task
{
	protected $sourceId = 19;

	protected $channelPrefix = '';
	protected $sourceName = '';
	protected $channelName = '';
	protected $channelId = -1;
	protected $channelURL = '';
	protected $channelURLBase = '';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

	/* crappy, yet standard */

//	function loadMVRpage($prefix, $logtitle, $logwhat, $num, $url, $urlbase, $retweet = false, $expectempty = false)
	function execute($html)
	{
		$xpath = $this->xpath($html);
		$items = $xpath ? $xpath->query("//ul[@class='categoryList']/li") : false;
		if (!$items || $items->length == 0) {
			if (!$expectempty)
				$this->reportError("Грешка при зареждане на отделно съобщение");
			return;
		}

		echo "Открити " . $items->length . " $logwhat\n";

		$query = array();
		foreach ($items as $item) {
			$item_1 = $xpath->query("h3/a", $item);
			$item_2 = $xpath->query("p[@class='dateOfLink']", $item);
			if ($item_1->length == 0 || $item_2->length == 0)
				continue;

			$url = $urlbase . Utils::cleanSpaces($item_1->item(0)->getAttribute("href"));
			$hash = md5($url);
			if (!$this->checkHash($hash))
				continue;

			$date = $item_2->item(0)->textContent;
			$date = Utils::bgMonth(Utils::cleanSpaces($date));
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < time() - 3600 * 24 * 7)
				continue;

			$title = $item_1->item(0)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = $prefix . $title;
			if (!$this->checkTitle($title))
				continue;

			$description = null;
			$media = array("image" => array());

			$html1 = $this->loadURL($url);
			if ($html1) {
				$xpath1 = $this->xpath($html1);
				$items1 = $xpath1->query("//table[@id='content']//p|//table[@id='content']//h3|//table[@id='content']//div[@id='images']");
				if ($items1->length > 0) {
					$description = "";
					foreach ($items1 as $item1)
						$description .= $item1->C14N();
					$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
					$description = mb_ereg_replace("\s?(title|name|style|class|id|alt|target|align|dir)=[\"'].*?[\"']\s?", " ", $description);
					$description = mb_ereg_replace("<p>[  ]*</p>|<br>[  ]*</br>|<a>[  ]*</a>|<div>[  ]*</div>", " ", $description);
					$description = mb_ereg_replace(" >", ">", $description);
					$description = Utils::cleanSpaces($description);
					$description = html_entity_decode($description);
				}

				$items2 = $xpath1->query("//table[@id='content']//div[@id='images']//a[text()='Илюстрация']|//table[@id='content']//p/a[text()='Снимки']");
				foreach ($items2 as $item2) {
					$magepageurl = $urlbase . $item2->getAttribute('href');
					$html3 = $this->loadURL($magepageurl);
					$items3 = $this->xpathDoc($html3, "//div[@id='divIllustrationHeap']//img|//div[@id='divIllustration']//img");
					foreach ($items3 as $item3) {
						$imageurl = $urlbase . $item3->getAttribute('src');
						$imageurl = $this->loadItemImage($imageurl, []);
						if ($imageurl) {
							$media["image"][] = array($imageurl);
						}
					}
				}
			}

			if (count($media["image"]) == 0)
				$media = null;
			$query[] = array($title, $description, $date, $url, $hash, $media);
		}

		echo "Възможни " . count($query) . " нови $logwhat\n";

		$itemids = $this->saveItems($query);
		if ($retweet == "lipsva")
			$this->queueTweets($itemids, "lipsva", "mibulgaria");
		else
			if ($retweet == "govalerteu")
				$this->queueTweets($itemids, "mibulgaria", "govalerteu");
			else
				$this->queueTweets($itemids, "mibulgaria");
	}

	/*
	------------------------------------------------------------------------
	*/

	function xpath($html)
	{
		if (!$html)
			return null;
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new DOMXpath($doc);
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
