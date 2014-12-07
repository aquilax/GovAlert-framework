<?php

/*
    Utils
*/

class Utils
{

	static function transliterate($textLat = null)
	{
		$cyr = array('Я', 'Ц', 'Ц', 'Ж', 'Ч', 'Щ', 'Ш', 'Ю', 'ЙО', 'С', 'ИЙ', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З',
			'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ъ', 'Я');
		$lat = array('YA', 'TS', 'TZ', 'ZH', 'CH', 'SHT', 'SH', 'YU', 'YO', 'SS', 'YI', 'A', 'B', 'V', 'G', 'D', 'E', 'Z',
			'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'A', 'J');
		return str_replace($lat, $cyr, $textLat);
	}

	static function cleanSpaces($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		return $text;
	}

	static function bgMonth($text)
	{
		$text = mb_ereg_replace("Януари|януари|ЯНУАРИ", "01", $text, "imsr");
		$text = mb_ereg_replace("Февруари|февруари|ФЕВРУАРИ", "02", $text, "imsr");
		$text = mb_ereg_replace("Март|март|МАРТ", "03", $text, "imsr");
		$text = mb_ereg_replace("Април|април|АПРИЛ", "04", $text, "imsr");
		$text = mb_ereg_replace("Май|май|МАЙ", "05", $text, "imsr");
		$text = mb_ereg_replace("Юни|юни|ЮНИ", "06", $text, "imsr");
		$text = mb_ereg_replace("Юли|юли|ЮЛИ", "07", $text, "imsr");
		$text = mb_ereg_replace("Август|август|АВГУСТ", "08", $text, "imsr");
		$text = mb_ereg_replace("Септември|септември|СЕПТЕМВРИ", "09", $text, "imsr");
		$text = mb_ereg_replace("Октомври|октомври|ОКТОМВРИ", "10", $text, "imsr");
		$text = mb_ereg_replace("Ноември|ноември|НОЕМВРИ", "11", $text, "imsr");
		$text = mb_ereg_replace("Декември|декември|ДЕКЕМВРИ", "12", $text, "imsr");
		return $text;
	}


	static function fixCase($text)
	{
		if (mb_convert_case($text, MB_CASE_UPPER) == $text ||
			mb_convert_case($text, MB_CASE_LOWER) == $text
		)
			return mb_convert_case($text, MB_CASE_TITLE);
		return $text;
	}

	static function replaceAccounts($title, $cutlen)
	{
		$map = array(
			"@KGeorgievaEU" => array("Кристалина Георгиева", "Кристалина"),
			"@CIKBG" => array("Централната избирателна комисия", "ЦИК"),
			"@BgPresidency" => array("Президентът на РБ", "Президента на РБ", "президентът на Република България", "президента на Република България", "президентът Плевнелиев", "президента Плевнелиев", "президентът Росен Плевнелиев", "президента Росен Плевнелиев"),
			"@EP_Bulgaria" => array("Европейски Парламент", "Европейския Парламент", "Европейският Парламент"),
			"@TomislavDonchev" => array("Томислав Дончев"),
			"@BoykoBorissov" => array("Бойко Борисов"),
			"@SvMalinov" => array("Светослав Малинов"),
			"@evapaunova" => array("Ева Паунова"),
			"@JunckerEU" => array("Юнкер"),
			"@IvailoKalfin" => array("Ивайло Калфин", "Калфин"),
			"@FandakovaY" => array("Йорданка Фандъкова", "Фандъкова"),
			"@Stoli4naOb6tina" => array("Столична община"),
			"@UniversitySofia" => array("Софийски университет"),
			"@MoskovPetar" => array("Петър Москов"),
			"@rmkanev" => array("Радан Кънев")
		);

		foreach ($map as $account => $strings)
			$title = self::replaceAccount($title, $account, $cutlen, $strings);

		return $title;
	}

	static function replaceAccount($title, $account, $cutlen, $texts)
	{
		$text = false;
		$loc = '';
		foreach ($texts as $textT)
			if (($loc = mb_stripos($title, $textT)) !== false) {
				$text = $textT;
				break;
			}
		if ($text === false || $loc + mb_strlen($account) >= $cutlen)
			return $title;
		$firstPart = mb_substr($title, 0, $loc);
		if (trim($firstPart) == '')
			$firstPart = ".";
		return $firstPart . $account . mb_substr($title, $loc + mb_strlen($text));
	}

	static function now()
	{
		return date('c');
	}

}

function linkCode($id)
{
	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$code = "";
	while ($id > 0) {
		$rest = $id % strlen($chars);
		$code = substr($chars, $rest, 1) . $code;
		$id = floor($id / strlen($chars));
	}
	return $code;
}

function codeToId($code)
{
	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$id = "";
	for ($i = 0; $i < strlen($code); $i++) {
		$pos = strpos($chars, substr($code, strlen($code) - 1 - $i, 1));
		if ($pos == -1)
			return false;
		$id += $pos * pow(strlen($chars), $i);
	}
	return $id;
}

function codeToUrl(Database $db, $code)
{
	if (!$code) {
		return false;
	}
	if (is_nan(intval($code)))
		return false;
	if (substr($code, 0, 1) == "-") {
		$id = codeToId(substr($code, 1));
		$query1 = "SELECT url FROM link WHERE linkid=$id LIMIT 1";
		$codetype = 'link';
	} else {
		$id = codeToId($code);
		$query1 = "SELECT url FROM item WHERE itemid=$id LIMIT 1";
		$codetype = 'item';
	}

	$res = $db->query($query1);
	if (!$res)
		return false;
	$row = $res->fetch_array();
	if (!$row)
		return false;
	if ($_SERVER['REMOTE_ADDR']) {
		$ip = explode('.', $_SERVER['REMOTE_ADDR']);
		$ip = sprintf("%02X%02X%02X%02X", intval($ip[0]), intval($ip[1]), intval($ip[2]), intval($ip[3]));
		$db->insert('visit', [
			'id' => $id,
			'type' => $codetype,
			'ip' => $ip,
		]);
	}
	return $row[0];
}




