<?php
function mailStrategy($mail) {
  $mail=strstr($mail,"\n\n");
  $mail=str_replace("\n","",$mail);
  $mail=base64_decode($mail);
  $linkstart=strpos($mail,"<a href=\"")+strlen("<a href=\"");
  $url=substr($mail,$linkstart,strpos($mail,"\">")-$linkstart);
  if ($url)
    strategy_processUrl($url);
}

function strategy_processUrl($url) {
  setSession(2,0);
  $html = loadURL($url);
  if (!$html)
    return;

  $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
  $doc = new DOMDocument("1.0", "UTF-8");
  $doc->preserveWhiteSpace=false;
  $doc->strictErrorChecking=false;
  $doc->encoding = 'UTF-8';
  $doc->loadHTML($html);
  $xpath = new DOMXpath($doc);  

  $items = $xpath->query("//div[@class='public_info_strategic']");
  if ($items->length==0)
    return;
  $item = $items->item(0);

  $hash = md5($item->textContent);
  $title = $item->childNodes->item(1)->textContent;
  $title = strategy_cleanText($title);
  $title = text_fixCase($title);
  $item->removeChild($item->childNodes->item(0));
  $description = $item->C14N();
  $description = mb_ereg_replace(" </","</",mb_ereg_replace("> ",">",$description));
  $description = mb_ereg_replace("\s?(title|name|style|id|class|xml\:lang)=[\"'].*?[\"']","",$description);
  $description = strategy_cleanText($description);
	$description = str_replace("\\r ","",$description);
  $description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>","",$description);
  $itemids = saveItem($title,$description,"now",$url,$hash);
  queueTweets($itemids);
}

function strategy_cleanText($text) {
  $text = str_replace(" "," ",$text);
	$text = mb_ereg_replace("[\n\r\t ]+"," ",$text);
  $text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
	$text = html_entity_decode($text);
	return $text;
}

?>