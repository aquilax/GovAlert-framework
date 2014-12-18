<?php

/*

0: земетресения http://ndc.niggg.bas.bg/data.xml

*/

namespace GovAlert\Tasks\Bas;

use GovAlert\Common\Database;

class BasZemetreseniq extends Base
{
	protected $categoryId = 0;
	protected $categoryName = 'земетресения';
	protected $categoryURL = 'http://ndc.niggg.bas.bg/data.xml';

	protected function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), '//marker');

		$query = [];
		foreach ($items as $item) {
			$mag = doubleval($item->getAttribute('mag'));

			$date = trim($item->getAttribute('time'));
			$lat = $item->getAttribute('lat');
			$lng = $item->getAttribute('lon');
			$hash = md5(substr($date, 0, -1));
			$inBG = $lat > 41.32 && $lat < 44.04 && $lng > 22.55 && $lng < 28.60;

			if ($mag < 3 && !($mag > 2 && $inBG)) {
				continue;
			}
			$date = strtotime($date . 'UTC');
			if ($date < $this->timeDiff('-1 day')) {
				continue;
			}
			$dateDiff = $this->db->time() - $date;
			if ($dateDiff < 60) {
				$dateDiff = "секунди";
			} elseif ($dateDiff < 100 * 60) {
				$dateDiff = round($dateDiff / 60) . ' мин.';
			} elseif ($dateDiff < 4 * 3600) {
				$dateDiff = round($dateDiff / 3600) . " ч.";
			} else {
				continue;
			}

			$date = date("Y-m-d H:i:s", $date);

			if (!$this->checkHash($hash)) {
				continue;
			}

			list($town, $direction) = $this->processor->getTownAndDirection($lat, $lng);
			$res = $this->db->query('SELECT grad, geo FROM s_bas');
			$town = null;
			$direction = null;
			if ($res->num_rows > 0) {
				while ($row = $res->fetch_array()) {
					$row[1] = explode(',', $row[1]);
					$directionNew = $this->direction($row[1][0], $row[1][1], $lat, $lng);
					if ($direction == null || $directionNew[0] < $direction[0]) {
						$town = $row[0];
						$direction = $directionNew;
						if ($direction[0] < 40) {
							break;
						}
					}
				}
			}

			if ($town == null) {
				$town = "Пловдив";
				$direction = $this->direction(42.141948, 24.7465238, $lat, $lng);
			}

			if ($direction[0] < 15) {
				$title = 'около ' . $town;
			} else {
				$title = 'на ' . $direction[0] . ' км ' . $direction[1] . ' от ' . $town;
			}
			$title = mb_ereg_replace(" ЮИ ", " югоизточно ", $title, "im");
			$title = mb_ereg_replace(" ЮЗ ", " югозападно ", $title, "im");
			$title = mb_ereg_replace(" СИ ", " североизточно ", $title, "im");
			$title = mb_ereg_replace(" СЗ ", " северозападно ", $title, "im");
			$title = mb_ereg_replace(" Ю ", " южно ", $title, "im");
			$title = mb_ereg_replace(" С ", " северно ", $title, "im");
			$title = mb_ereg_replace(" И ", " източно ", $title, "im");
			$title = mb_ereg_replace(" З ", " западно ", $title, "im");
			$title = "Земетресение $mag $title преди $dateDiff";

			$description = Utils::cleanSpaces($item->getAttribute("location"));
			if ($description == '') {
				$description = null;
			}

			$media = [
				"geo" => ["$lat,$lng", null]
			];

			if ($inBG || $mag >= 4.5) {
				$media['geoimage'] = array($this->loadGeoImage($lat, $lng, 8), null);
			}
			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => 'http://ndc.niggg.bas.bg',
				'hash' => $hash,
				'media' => $media
			];
		}
		return $query;
	}

	protected function processItems(Array $query) {
		$this->logger->info('Възможни ' . count($query) . ' нови ' . $this->categoryName);
		$itemIds = $this->saveItems($query);
		if (count($itemIds) <= 3) {
			$this->queueTweets($itemIds);
		} else {
			$this->queueTextTweet("В последните минути имаше " . count($itemIds) . " земетресения", "http://ndc.niggg.bas.bg");
		}
	}

	private function direction($lat1, $lng1, $lat2, $lng2)
	{
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;

		$r = 6372.797;
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = floor($r * $c);

		$bearing = atan2(cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dlng), sin($dlng) * cos($lat2)) / M_PI;
		if ($bearing < 0.125 && $bearing > -0.125)
			$bearing = "И";
		else if ($bearing >= 0.125 && $bearing < 0.375)
			$bearing = "СИ";
		else if ($bearing >= 0.375 && $bearing < 0.625)
			$bearing = "С";
		else if ($bearing >= 0.625 && $bearing < 0.875)
			$bearing = "СЗ";
		else if ($bearing <= -0.125 && $bearing > -0.375)
			$bearing = "ЮИ";
		else if ($bearing <= -0.375 && $bearing > -0.625)
			$bearing = "Ю";
		else if ($bearing <= -0.625 && $bearing > -0.875)
			$bearing = "ЮЗ";
		else
			$bearing = "З";

		return [$km, $bearing];
	}
}

