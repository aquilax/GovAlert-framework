<?php

/*

0: съобщения http://bnb.bg/PressOffice/POPressReleases/POPRDate/index.htm
1: платежен баланс http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSBalancePayments/index.htm
2: брутен външен дълг http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSGrossExternalDebt/index.htm
3: парични депозити и кредитни показатели http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSMonetaryStatistics/POPRSMonetarySurvey/index.htm
4: Депозити и кредити по количествени категории и икономически дейности http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSMonetaryStatistics/POPRSDepositsLoans/index.htm
5: лихвена статистика http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInterestRate/index.htm
6: лизингови дружества http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSLeasingCompanies/index.htm
7: инвестиционни фондове http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInvestmentFonds/index.htm
8: Дружества, специализирани в кредитиране http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSLendingCorporations/index.htm
9: Статистика на застрахователната дейност http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInsuranceCompanies/index.htm

*/


class Bnb extends Task
{

	function bnb_Saobshtenia()
	{
		$this->logger->info('> Проверявам за съобщения в БНБ');
		$this->setSession(15, 0);

		$html = $this->loadURL("http://bnb.bg/PressOffice/POPressReleases/POPRDate/index.htm", 0);
		if (!$html) {
			return;
		}
		$items = $this->xpathDoc($html, "//div[@id='main']//h3/a");
		if (!$items || $items->length == 0) {
			$this->reportError('Грешка при зареждане на страницата');
			return;
		}

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-3 day")) {
				continue;
			}
			$url = "http://bnb.bg/PressOffice/POPressReleases/POPRDate/" . $item->getAttribute("href");
			$hash = md5($url);

			$html1 = $this->loadURL($url);
			if (!$html1) {
				return;
			}
			$items1 = $this->xpathDoc($html1, "//div[@class='doc_entry']");
			if (!$items1 || $items1->length == 0) {
				$this->reportError("Грешка при зареждане на отделно съобщение");
				return;
			}
			$title = $items1->item(0)->textContent;
			$title = $this->cleanText($title);
			if (mb_strpos($title, "ПРЕССЪОБЩЕНИЕ") !== false) {
				if (mb_strpos($title, "г.") !== null && mb_strpos($title, "г.") < 50) {
					$title = mb_substr($title, mb_strpos($title, "г.") + 3);
				} else {
					$title = mb_substr($title, 14);
				}
			}
			$title = "Съобщение: $title";

			$description = $items1->item(0)->C14N();
			$description = $this->cleanDescr($description);

			$query[] = array($title, $description, $date, $url, $hash);
		}
		$this->logger->info('Възможни ' . count($query) . ' нови съобщения');
		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds);
	}

	function bnb_PlatejenBalans()
	{
		$this->statsHandling(1, "Платежен баланс", "ПЛАТЕЖЕН БАЛАНС",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSBalancePayments/");
	}

	function bnb_BrutenVanshenDalg()
	{
		$this->statsHandling(2, "Брутен външен дълг", "БРУТЕН ВЪНШЕН ДЪЛГ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSGrossExternalDebt");
	}

	function bnb_ParichniDepositi()
	{
		$this->statsHandling(3, "Парични, депозитни и кредитни показатели", "ПАРИЧНИ, ДЕПОЗИТНИ И КРЕДИТНИ ПОКАЗАТЕЛИ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSMonetaryStatistics/POPRSMonetarySurvey");
	}

	function bnb_KreditiDepositi()
	{
		$this->statsHandling(4, "Депозити и кредити по количествени категории и икономически дейности", "ДЕПОЗИТИ И КРЕДИТИ ПО КОЛИЧЕСТВЕНИ КАТЕГОРИИ И ИКОНОМИЧЕСКИ ДЕЙНОСТИ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSMonetaryStatistics/POPRSDepositsLoans");
	}

	function bnb_LihvenaStatistika()
	{
		$this->statsHandling(5, "Лихвена статистика", "ЛИХВЕНА СТАТИСТИКА",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInterestRate");
	}

	function bnb_LizingoviDrujestva()
	{
		$this->statsHandling(6, "Статистика на лизинговата дейност", "СТАТИСТИКА НА ЛИЗИНГОВАТА ДЕЙНОСТ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSLeasingCompanies");
	}

	function bnb_InvesticionniFondove()
	{
		$this->statsHandling(7, "Статистика на инвестиционните фондове", "СТАТИСТИКА НА ИНВЕСТИЦИОННИТЕ ФОНДОВЕ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInvestmentFonds");
	}

	function bnb_KoreditiraneDrujestva()
	{
		$this->statsHandling(8, "Дружества, специализирани в кредитиране", "СТАТИСТИКА НА ДРУЖЕСТВАТА, СПЕЦИАЛИЗИРАНИ В КРЕДИТИРАНЕ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSLendingCorporations");
	}

	function bnb_ZastrahovatelnaDeinost()
	{
		$this->statsHandling(9, "Статистика на застрахователната дейност", "СТАТИСТИКА НА ЗАСТРАХОВАТЕЛНАТА ДЕЙНОСТ",
			"http://bnb.bg/PressOffice/POStatisticalPressReleases/POPRSInsuranceCompanies");
	}


	function statsHandling($category, $tweet, $titleBig, $url)
	{
		echo "> Проверявам за $tweet в БНБ\n";
		$this->setSession(15, $category);

		$html = $this->loadURL("$url/index.htm", $category);
		if (!$html) return;
		$items = $this->xpathDoc($html, "//div[@id='main']//h4/a");
		if (!$items || $items->length == 0) {
			$this->reportError("Грешка при зареждане на страницата");
			return;
		}
		$query = array();
		foreach ($items as $item) {
			$url = $url . "/" . $item->getAttribute("href");
			$hash = md5($url);

			$html1 = $this->loadURL($url);
			if (!$html1) return;
			$xpath1 = $this->xpath($html1);
			if (!$xpath1) {
				$this->reportError("Грешка при зареждане на отделно съобщение");
				return;
			}
			$items1 = $xpath1->query("//div[@class='doc_entry']");
			if (!$items1 || $items1->length == 0) {
				$this->reportError("Грешка при зареждане на отделно съобщение");
				return;
			}
			$date = $items1->item(0)->textContent;
			$date = Utils::cleanSpaces($date);
			$datepos = mb_strpos($date, " ", mb_strlen("ПРЕССЪОБЩЕНИЕ")) + 1;
			$date = mb_substr($date, $datepos, mb_strpos($date, "ч.") - $datepos - 1);
			$date = Utils::bgMonth($date);
			$date = explode(" ", $date);
			$date = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $date[4];

			$title = $items1->item(0)->textContent;
			$title = explode("\n", $title);
			for ($i = 0; $i < count($title); $i++)
				if (mb_substr($title[$i], 0, mb_strlen($titleBig)) == $titleBig)
					$title = mb_substr($title[$i + 2], 0, -3);
			if (mb_strlen($title) > 20) {
				$this->reportError("Грешка във формата на страницата");
				return;
			}
			$title = mb_convert_case($title, MB_CASE_LOWER);
			$title = "$tweet за $title";

			$media = null;
			$items2 = $xpath1->query(".//img", $items1->item(0));
			foreach ($items2 as $item2) {
				$imageURL = "http://bnb.bg" . $item2->getAttribute("src");
				$imageURL = $this->loadItemImage($imageURL, []);
				if ($imageURL == null) continue;
				if ($media == null)
					$media = array("image" => array($imageURL, null));
				else {
					if (!is_array($media["image"][0]))
						$media["image"] = array($media["image"]);
					$media["image"][] = array($imageURL, null);
				}
			}

			$description = $items1->item(0)->C14N();
			$description = $this->cleanDescr($description);

			$query[] = array($title, $description, $date, $url, $hash, $media);
		}
		$this->logger->info('Възможни ' . count($query) . ' нови tweet');

		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds);
	}

	/**
	 * @param $html
	 * @param $q
	 * @return array|DOMNodeList
	 */
	private function xpathDoc($html, $q)
	{
		$xpath = $this->xpath($html);
		if (!$xpath)
			return array();
		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}

	private function xpath($html)
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

	private function cleanText($text)
	{
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}

	private function cleanDescr($description)
	{
		$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
		$description = mb_ereg_replace("\s?(title|name|style|class|id|bordercolor)=[\"'].*?[\"']\s?", "", $description);
		$description = mb_ereg_replace("<p>[  ]*</p>|<span>[  ]*</span>|<comment>[  ]*</comment>|<a>[  ]*</a>|<div>[  ]*</div>|<img[^>]*?></img>|<img[^>]*?/>", "", $description);
		$description = $this->cleanText($description);
		return $description;
	}

}
