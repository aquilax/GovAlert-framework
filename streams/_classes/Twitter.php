<?php

class Twitter
{
	private $db;
	private $logger;

	function __construct(Database $db, Logger $logger) {
		$this->db = $db;
		$this->logger = $logger;
	}

	function getUrlFileType($url)
	{
		if (strpos($url, ".pdf") !== false)
			return "[PDF]";
		if (strpos($url, ".doc") !== false)
			return "[DOC]";
		if (strpos($url, ".xls") !== false || strpos($url, ".xlsx") !== false)
			return "[XLS]";

		$context = stream_context_create(array('http' => array('method' => 'HEAD')));
		$fd = fopen($url, 'rb', false, $context);
		$data = stream_get_meta_data($fd);
		fclose($fd);
		if (!$data['wrapper_data'])
			return false;

		foreach ($data['wrapper_data'] as $wr)
			if (strpos($wr, "Content-Disposition: attachment") !== false) {
				if (strpos($wr, ".pdf") !== false)
					return "[PDF]";
				if (strpos($wr, ".doc") !== false)
					return "[DOC]";
				if (strpos($wr, ".xls") !== false || strpos($url, ".xlsx") !== false)
					return "[XLS]";
			}
		return false;
	}

	function postTwitter()
	{
		$twitterAuth = array();
		$res = $this->db->query("SELECT handle, token, secret FROM twitter_auth");
		while ($row = $res->fetch_assoc()) {
			$twitterAuth[strtolower($row['handle'])] = array($row['token'], $row['secret']);
		}
		$res->free();

		$res = $this->db->query("select t.tweetid, t.itemid, t.text, t.sourceid, t.account, t.retweet, i.title, i.url, s.shortname, s.geo, count(m.type) media from tweet t left outer join item i on i.itemid=t.itemid left outer join source s on i.sourceid=s.sourceid or t.sourceid=s.sourceid left outer join item_media m on m.itemid=t.itemid where error is null group by t.itemid order by t.account, t.priority desc, t.queued, t.itemid limit 5");

		if ($res->num_rows > 0) {
			echo "Изпращам " . $res->num_rows . " tweet/s\n";

			require_once('/www/govalert/twitter/twitteroauth/twitteroauth.php');
			require_once('/www/govalert/twitter/config.php');

			$currentAccount = false;
			$connection = false;

			$first = true;
			while ($row = $res->fetch_assoc()) {
				if (!$first) {
					sleep(20);
				}
				$first = false;

				if ($connection == false || $currentAccount === false || $currentAccount != strtolower($row['account'])) {
					$currentAccount = strtolower($row['account']);
					$currentAuth = $twitterAuth[$currentAccount];
					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $currentAuth[0], $currentAuth[1]);
					$connection->host = "https://api.twitter.com/1.1/";
					$connection->useragent = 'Activist Dashboard notifier';
					$connection->ssl_verifypeer = TRUE;
					$connection->content_type = 'application/x-www-form-urlencoded';
				}

				if ($row['retweet'] && !$row['itemid'] && !$row['text']) {
					$tres = $connection->post('statuses/retweet/' . $row['retweet']);
				} else {

					$uploadimages = array();
					$geo = explode(",", $row['geo']);
					if (intval($row['media']) != 0) {
						$connection->host = "https://upload.twitter.com/1.1/";

						$resmedia = $this->db->query("select type,value from item_media where itemid='" . $row['itemid'] . "' limit 3");
						while ($rowmedia = $resmedia->fetch_assoc()) {
							if ($rowmedia['type'] == "geo")
								$geo = explode(",", $rowmedia['value']);
							elseif ($rowmedia['type'] == "image" || $rowmedia['type'] == "geoimage") {
								$mediares = $connection->upload('media/upload', array(
									'media' => "@" . $rowmedia['value']
								));
								if (!$mediares->error && $mediares->media_id_string) {
									$uploadimages[] = $mediares->media_id_string;
								}
							}
						}
						$resmedia->free();

						$connection->host = "https://api.twitter.com/1.1/";
					}
					if (count($uploadimages) == 0)
						$uploadimages = false;

					$messagelen = 140;
					$prefix = "";
					$postfix = "";
					if ($row['text'] == null) {
						$postfix = " http://GovAlert.eu/" . linkCode(intval($row['itemid']));
						// TODO: Figure out message
						if ($row['url'] != null && mb_strlen($message) <= 134) {
							$urltype = $this->getUrlFileType($row['url']);
							if ($urltype) {
								$postfix .= " $urltype";
								$messagelen -= 6;
							}
						}
						$title = $row['title'];
					} else {
						$title = $row['text'];
					}
					if (mb_substr($title, 0, 8) != "@yurukov" && $row['account'] == "govalerteu")
						$prefix = "[${row['shortname']}] ";
					$messagelen -= ($row['text'] == null ? 23 : 0) + ($uploadimages ? 23 : 0) + mb_strlen($prefix);

					$title = Utils::replaceAccounts($title, $messagelen);

					if (mb_strlen($title) > $messagelen) {
						$title = mb_substr($title, 0, $messagelen - 1) . "…";
					}
					$message = $prefix . $title . $postfix;

					$params = array(
						'status' => $message,
						'lat' => $geo[0],
						'long' => $geo[1],
						'place_id' => '1ef1183ed7056dc1',
						'trim_user' => 'true',
						'display_coordinates' => 'true'
					);
					if ($uploadimages) {
						$params["media_ids"] = implode(",", $uploadimages);
					}

					$tres = $connection->post('statuses/update', $params);

					if ($row['retweet'] && !$tres->errors) {
						$tweetid = $this->db->escape_string($tres->id_str);
						$accounts = explode(",", $row['retweet']);
						$query = array();
						foreach ($accounts as $account) {
							$query[] = "('$account',now(),'$tweetid')";
						}
						$this->db->query("insert LOW_PRIORITY ignore into tweet (account, queued, retweet) values " . implode(",", $query));
					}
				}

				if ($tres->code == 215) {
					$this->logger->error('Грешка: временна грешка на ауторизацията.');
				} else {
					if ($tres->errors) {
						$this->logger->error('Грешка: '. $message);
						$errortext = $this->db->escape_string(json_encode($tres));
						$this->db->query("update tweet set error='$errortext' where tweetid=${row['tweetid']} limit 1");
						break;
					} else {
						$this->db->query("delete from tweet where tweetid=${row['tweetid']} limit 1");
					}
				}
			}

		}
		$res->free();
	}
}