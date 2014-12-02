<?php
/*
    Loading data
*/

function getUrlFileType($url) {
	if (strpos($url,".pdf")!==false)
		return "[PDF]";
	if (strpos($url,".doc")!==false)
		return "[DOC]";
	if (strpos($url,".xls")!==false || strpos($url,".xlsx")!==false)
		return "[XLS]";

	$context  = stream_context_create(array('http' =>array('method'=>'HEAD')));
	$fd = fopen($url, 'rb', false, $context);
	$data = stream_get_meta_data($fd);
	fclose($fd);
	if (!$data['wrapper_data'])
		return false;

	foreach ($data['wrapper_data'] as $wr)
		if (strpos($wr,"Content-Disposition: attachment")!==false) {
			if (strpos($wr,".pdf")!==false)
				return "[PDF]";
			if (strpos($wr,".doc")!==false)
				return "[DOC]";
			if (strpos($wr,".xls")!==false || strpos($url,".xlsx")!==false)
				return "[XLS]";
		}
	return false;
}

function setPageLoad($url,$loadstart) {
	global $link,$session,$debug;
	if ($debug)
		return;
	if (!checkSession())
		return;
	$loadtime = round((microtime(true)-$loadstart)*1000);
	$res = $link->query("insert LOW_PRIORITY ignore into scrape_load (sourceid,category,url,loadtime) value ".
		"(".$session["sourceid"].",".$session["category"].",'$url',$loadtime)") or reportDBErrorAndDie();
}
/*
function checkPageChanged($html,$linki) {
  global $link,$session;
  if (!checkSession())
    return false;
  $hash = md5($html);
  $res = $link->query("select hash from scrape where hash='$hash' and sourceid=".$session["sourceid"]." and url=$linki limit 1") or reportDBErrorAndDie();
  if ($res->num_rows>0) {
    $res->free();
    return false;
  }

  $res->free();
  $link->query("replace scrape (sourceid,url,hash,loadts) value (".$session["sourceid"].",$linki,'$hash',now())") or reportDBErrorAndDie();
  return true;
}
*/
function loadGeoImage($lat,$lng,$zoom) {
	$filename = "/www/govalert/media/maps/static/".str_replace(".","_",$lat."_".$lng)."_$zoom.png";
	if (!file_exists($filename)) {
		$url = "http://api.tiles.mapbox.com/v3/yurukov.i6nmgf1c/pin-l-star+ff0000($lng,$lat,$zoom)/$lng,$lat,$zoom/640x480.png";
		$loadstart=microtime(true);
		exec("wget --header='Connection: keep-alive' --header='Cache-Control: max-age=0' --header='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' --header='User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36' --header='Accept-Encoding: gzip,deflate,sdch' --header='Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4' -q -O '$filename' '$url'");
		setPageLoad($url,$loadstart);
		usleep(500000);
	}

	if (!file_exists($filename) || filesize($filename)==0) {
		reportError("Грешка при зареждане на геоснимка $lat,$lng,$zoom.");
		return null;
	}

	return $filename;
}

function loadItemImage($url,$type=null,$options) {
	if ($type===null) {
		$type=".jpg";
	} else if (substr($type,0,1)!=".")
		$type=".$type";

	if (strtolower($type)!=".jpg" && strtolower($type)!=".jpeg" && strtolower($type)!=".gif" && strtolower($type)!=".png" && strtolower($type)!=".bmp")
		return null;

	$filename = "/www/govalert/media/item_images/".md5($url).($type==".bmp"?".jpg":$type);
	if (!file_exists($filename)) {
		$loadstart=microtime(true);
		exec("wget --header='Connection: keep-alive' --header='Cache-Control: max-age=0' --header='Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' --header='User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36' --header='Accept-Encoding: gzip,deflate,sdch' --header='Accept-Language: en-US,en;q=0.8,bg;q=0.6,de;q=0.4' -q -O '$filename' '$url'");
		setPageLoad($url,$loadstart);
		if (filesize($filename)>=1.5*1024*1024)
			resizeItemImage($filename,$type);
		else
			fitinItemImage($filename,$type,$options);

		usleep(500000);
	}

	if (!file_exists($filename) || filesize($filename)==0) {
		if (file_exists($filename))
			unlink($filename);
		if (!array_key_exists("doNotReportError",$options))
			reportError("Грешка при зареждане на снимка: $url");
		return null;
	}

	return $filename;
}
