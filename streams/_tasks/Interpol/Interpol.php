<?php

/*
Links
1-50: безследно изчезнали http://www.interpol.int/notice/search/missing/(offset)/0/(Nationality)/122/(current_age_maxi)/100/(search)/1
51-100:  издирвани http://www.interpol.int/notice/search/wanted/(offset)/0/(Nationality)/122/(current_age_maxi)/100/(search)/1
101: новини http://www.interpol.int/Member-countries/Europe/Bulgaria
*/

abstract class Interpol extends Task
{
	protected $sourceId = 18;
	protected $sourceName = 'Интерпол';

	protected function interpolLoad($prop)
	{
		$codes = array();
		$data = array();
		$available = array();

		$res = $this->db->query("select code from s_interpol where removed is null and missing=${prop[1]}");
		while ($row = $res->fetch_array())
			$available[] = $row[0];
		$res->free();

		foreach ($prop[2] as $propU) {

			$html = $this->loadURL(sprintf($propU[0], 0), $propU[1] + 1);
			if (!$html) return;
			$xpath = $this->xpath($html);
			if (!$xpath) {
				$this->reportError("Грешка при зареждане на начална страница");
				return;
			}
			$items = $xpath->query("//div[@class='bloc_pagination']");
			if (!$items || $items->length == 0) {
				$this->reportError("Грешка при откриване на бройка");
				return;
			}
			$profiles = $items->item(0)->textContent;
			$profiles = intval(str_replace("Search result : ", "", $profiles));

			echo "Открити " . $profiles . " профила. Преглеждам...\n";

			for ($skip = 0; $skip < $profiles; $skip += 9) {
				if ($skip > 0) {
					$html = $this->loadURL(sprintf($propU[0], $skip), $propU[1] + $skip / 9 + 1);
					if (!$html) return;
					$xpath = $this->xpath($html);
					if (!$xpath) {
						$this->reportError("Грешка при зареждане на страница " . ($skip / 9 + 1));
						return;
					}
				}

				$items = $xpath->query("//div[@class='bloc_bordure']/div");
				if (!$items || $items->length == 0) {
					$this->reportError("Грешка при откриване нa профили на страница " . ($skip / 9 + 1));
					return;
				}
				foreach ($items as $item) {
					$code = $item->childNodes->item(5)->childNodes->item(1)->getAttribute('href');
					$code = substr($code, strrpos($code, '/') + 1);
					$code = $this->db->escape_string($code);
					if (in_array($code, $codes))
						continue;
					$photo = $item->childNodes->item(1)->firstChild->getAttribute('src');
					if (substr($photo, -16) != 'NotAvailable.gif') {
						$photo = str_replace('GetThumbnail', 'GetPicture', $photo);
						$photo = str_replace(array(' ', '%20'), '', $photo);
						$photo = $this->db->escape_string($photo);
					}
					$name = $item->childNodes->item(3)->childNodes->item(3)->childNodes;
					$name = $name->item(2)->textContent . ' ' . $name->item(0)->textContent;
					$name = mb_convert_case(Utils::transliterate(mb_convert_case($name, MB_CASE_UPPER)), MB_CASE_TITLE);
					$name = $this->db->escape_string($name);
					$codes[] = $code;
					$data[$code] = array($name, $photo);
				}
			}
		}

		$remove = array_diff($available, $codes);
		$add = array_diff($codes, $available);

		echo "Открити са " . count($add) . " нови съобщения и " . count($remove) . " за премахване.\n";

		if (count($remove) > 0) {
			$this->db->query("update s_interpol set removed=now() where code in ('" . implode("','", $remove) . "')");
		}
		if (count($add) > 0)
			foreach ($add as $code) {
				$this->db->query("insert into s_interpol (code,name,added,photo,missing) value ('$code','" . $data[$code][0] . "',now(),'" . $data[$code][1] . "',${prop[1]}) ON DUPLICATE KEY UPDATE removed=null");
			}

	}


	protected function interpolProcess($prop)
	{
		$query = array();
		$codes = array();
		$res = $this->db->query("SELECT code,name,added,photo FROM s_interpol where processed=0 and missing=${prop[1]} and removed is null");
		echo "> Има " . $res->num_rows . " ${prop[0]} без да са обявени тук. Зареждам снимките.\n";
		if ($res->num_rows == 0) {
			return;
		}
		while ($row = $res->fetch_assoc()) {
			$old = strtotime($row["added"]) < strtotime("-2 days");
			$noimage = substr($row["photo"], -16) == 'NotAvailable.gif';

			$media = null;
			if (!$noimage) {
				$url = $prop[5] . "/" . $row["code"];
				$this->loadURL($url);
				$imgoptions = array('doNotReportError' => 1, 'addInterpol' => ($prop[1] == 1 ? 'yellow' : 'red'));
				$imageurl = $this->loadItemImage("http://www.interpol.int" . $row["photo"], null, $imgoptions);
				if ($imageurl == null) {
					$imageurl = "http://www.interpol.int" . str_replace("ws/", "ws/%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20%20", $row["photo"]);
					$imageurl = $this->loadItemImage($imageurl, null, $imgoptions);
				}
				if ($imageurl != null) {
					$imagetitle = $row["name"];
					$media = array("image" => array(array($imageurl, $imagetitle)));
				}
			}
			if ($media == null && !$old && !$noimage)
				continue;

			$suffix = mb_substr($row["name"], -1) == 'а' ? 'а' : '';
			$title = sprintf($prop[2], $row["name"], $suffix);
			$hash = md5($row["code"]);
			$query[] = array($title, null, 'now', $url, $hash, $media);
			$codes[] = $row["code"];
		}

		echo "Възможни " . count($query) . " нови ${prop[0]}\n";
		$itemids = $this->saveItems($query);
		if (count($itemids) > 5)
			$this->queueTextTweet(sprintf($prop[3], count($itemids)), $prop[3], $prop[6], $prop[7]);
		else
			$this->queueTweets($itemids, $prop[6], $prop[7]);

		if (count($codes) > 0) {
			echo "Маркирам " . count($codes) . " ${prop[0]} като съобщени\n";
			$this->db->query("update s_interpol set processed=1 where code in ('" . implode("','", $codes) . "')");
		}
	}


	protected function xpath($html)
	{
		if (!$html) return null;
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new DOMXpath($doc);
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

}
