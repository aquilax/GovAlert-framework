<?php
/*
Links
0: информация http://www.dans.bg/bg/component/bca-rss-syndicator/?feed_id=1
*/

class Dans {

	function dansInformaciq() {

		echo "> Проверявам за новини в ДАНС\n";
		$this->setSession(17,1);

		$html = $this->loadURL("http://www.dans.bg/bg/component/bca-rss-syndicator/?feed_id=1",4);
		if (!$html) return;
		$xpath = $this->xpath($html, false);
		if (!$xpath) {
			reportError("Грешка при зареждане на отделно съобщение");
			return;
		}
		$items = $xpath->query("//item");
		if (!$items || $items->length==0) {
			$this->db->reportError("Грешка при зареждане на отделно съобщение");
			return;
		}

		echo "Открити ".$items->length." новини\n";

		$date = $xpath->query("//lastBuildDate");
		if (!$date || $date->length==0) {
			$this->reportError("Грешка при намирането на последната дата.");
			return;
		}

		$date = strtotime($date->item(0)->textContent);
		if ($date<strtotime("-1 week"))
			$items=array();
		$date = date("Y-m-d H:i:s",$date);

		$query=array();
		foreach ($items as $item) {
			if ($item->childNodes->length!=5)
				continue;

			$title = $item->childNodes->item(0)->textContent;
			$title = Utils::cleanSpaces($title);

			$url = $item->childNodes->item(1)->textContent;
			$url = str_replace("dans.int/","dans.bg/",$url);
			$hash = md5($url);
			if (!checkHash($hash))
				continue;

			$description = $item->childNodes->item(2)->textContent;
			$description = mb_ereg_replace(" </","</",mb_ereg_replace("> ",">",$description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?"," ",$description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>"," ",$description);
			$description = Utils::cleanSpaces($description);
			$description = html_entity_decode($description);

			$media=array("image" => array());
			$xpathsub = $this->xpath($item->childNodes->item(2)->textContent,true);
			$itemimgs = $xpathsub->query(".//a[img]");
			foreach ($itemimgs as $itemimg) {
				$imageurl = $itemimg->getAttribute("href");
				$imageurl = str_replace("http://www.dans.int/","",$imageurl);
				if (strpos($imageurl,"dans.bg")===false)
					$imageurl="http://www.dans.bg/".$imageurl;
				$media["image"][] = array(loadItemImage($imageurl, []));
				$imageurl = loadItemImage($imageurl, []);
				if ($imageurl)
					$media["image"][] = array($imageurl);
			}
			if (count($media["image"])==0)
				$media=null;

			$query[]=array($title,$description,$date,$url,$hash,$media);
		}
		echo "Възможни ".count($query)." нови новини\n";
		$itemids = $this->saveItems($query);
		queueTweets($itemids,'GovAlertEU','MIBulgaria');
	}

	/*
	------------------------------------------------------------------------
	*/

	function xpath($xml,$isHTML) {
		if (!$xml)
			return null;
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace=false;
		$doc->strictErrorChecking=false;
		$doc->encoding = 'UTF-8';
		if ($isHTML)
			$doc->loadHTML($xml);
		else
			$doc->loadXML($xml);
		return new DOMXpath($doc);
	}

} 