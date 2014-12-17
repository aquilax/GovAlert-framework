<?php

class RetweetAccounts extends Retweet
{

	protected $categoryId = 1;
	protected $categoryName = 'съобщения';
	protected $categoryURL = '';


	function execute($html)
	{
		$currentHour = intval(date("H"));

		if ($currentHour < 9 || $currentHour > 19) {
			$this->logger->info('> Ще проверявам за tweet-ове само през деня.');
			return;
		}

		$attempts = 4;
		$minActivity = 3;
		$maxtimetowait = 2 * 60 * 60 * 24;
		$mintimetocheck = 60 * 15;
		$maxtimetocheck = 60 * 60 * 24 * 4 + $mintimetocheck;
		$kirilica = array('а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'й', 'к', 'л',
			'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ш', 'щ', 'ъ', 'ь', 'ю', 'я');

		while ($attempts > 0) {
			$attempts--;

			$res = $this->db->query("SELECT twitter, lasttweet, lastretweet, if(tw_num=0,0,(tw_rts+tw_fav)/tw_num*2/3) " .
				"FROM s_retweet order by lastcheck asc limit 1");

			$account = 'BgPresidency';
			$lasttweet = null;
			$forceRT = false;
			$avgActivity = $minActivity;
			if ($row = $res->fetch_array()) {
				$account = $row[0];
				$lasttweet = $row[1];
				$forceRT = $row[2] == null || (time() - strtotime($row[2])) > $maxtimetowait;
				$avgActivity = $row[3] > $minActivity ? $row[3] : $minActivity;
			}
			$res->free();

			$this->logger->info('> Проверявам за tweets в на $account [спешно=' . ($forceRT ? 1 : 0) . ", средна активност=$avgActivity]");

			require_once(Config::get('twitterOAuth'));
			require_once(Config::get('twitterOAuthConfig'));

			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, CONSUMER_TOKEN_GOVALERTEU, CONSUMER_TOKEN_SECRET_GOVALERTEU);
			$connection->host = "https://api.twitter.com/1.1/";
			$connection->useragent = 'Activist Dashboard notifier';
			$connection->ssl_verifypeer = TRUE;

			$props = array(
				'screen_name' => $account,
				'trim_user' => true,
				'contributor_details' => false,
				'include_rts' => false
			);
			if ($lasttweet != null)
				$props['since_id'] = $lasttweet;
			$tres = $connection->get('statuses/user_timeline', $props);

			$tweets = array();
			foreach ($tres as $tweet) {
				if ($lasttweet != null && $tweet->id_str <= $lasttweet)
					break;
				$period = time() - strtotime($tweet->created_at);
				if ($period > $maxtimetocheck || $period < $mintimetocheck)
					break;

				$otgovor = $tweet->in_reply_to_status_id_str == "" || $tweet->in_reply_to_status_id_str == null;
				$zaBulgaria = 0;
				$textTweet = mb_convert_case($tweet->text, MB_CASE_LOWER);
				foreach ($kirilica as $bukva)
					if (mb_strpos($textTweet, $bukva) !== false) {
						$zaBulgaria = 2;
						break;
					}
				if ($zaBulgaria == 0 && mb_strpos($textTweet, "bulgaria") !== false)
					$zaBulgaria = 1;

				$tweets[] = array($tweet->retweet_count, $tweet->favorite_count, $tweet->id_str, $zaBulgaria, $otgovor);
			}

			$this->logger->info('> Открих ' . count($tweets) . ' tweet-а от последния RT');
			if (count($tweets) > 0) {
				usort($tweets, ['RetweetAccounts', 'sortTweets']);

				if ($tweets[0][0] + $tweets[0][1] >= $avgActivity || ($forceRT && $tweets[0][0] + $tweets[0][1] >= $minActivity)) {
					$this->logger->info('> Tweet-а с най-много интерес (' . ($tweets[0][0] + $tweets[0][1]) . ') ще бъде RT-нат.');

					$this->db->query("UPDATE s_retweet SET lasttweet='" . $tweets[0][2] . "', lastretweet=now(), lastcheck=now(), " .
						"tw_rts=tw_rts+" . $tweets[0][0] . ", tw_fav=tw_fav+" . $tweets[0][1] . ", tw_num=tw_num+1 " .
						"WHERE twitter='$account' limit 1");

					$this->db->insert('tweet', [
						'account' => 'govalerteu',
						'queued' => $tweets[0][2],
						'retweet' => '',
					]);
					break;
				}
			}

			$this->db->query("UPDATE s_retweet SET lastcheck = now() WHERE twitter = '$account' LIMIT 1");
		}
	}

	static function sortTweets($a, $b)
	{
		if ($a[3] != $b[3])
			return $a[3] > $b[3] ? -1 : 1;
		if ($a[4] != $b[4])
			return $b[4] ? -1 : 1;
		else if ($a[0] + $a[1] == $b[0] + $b[1])
			return $a[0] != $b[0] ? 0 : ($a[0] > $b[0] ? -1 : 1);
		else
			return $a[0] + $a[1] > $b[0] + $b[1] ? -1 : 1;
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}

}
