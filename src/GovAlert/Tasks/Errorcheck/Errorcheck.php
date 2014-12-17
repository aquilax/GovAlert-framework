<?php

namespace GovAlert\Tasks\Errorcheck;

class Errorcheck extends \GovAlert\Tasks\Task
{
	protected $sourceId = 0;
	protected $sourceName = 'липса на новини и възможни грешки';
	protected $categoryId = 0;
	protected $categoryName = '';
	protected $categoryURL = false;
	private $shortName = '';

	function execute($html)
	{
		$res = $this->db->query('SELECT * FROM (SELECT s.sourceid sourceid, s.shortname shortname, s.url url, MAX(i.readts) lastread, COUNT(i.itemid) items FROM source s LEFT OUTER JOIN item i ON s.sourceid=i.sourceid GROUP BY s.name ORDER BY MAX(readts) ASC) a WHERE a.lastread < subdate(now(), INTERVAL 2 WEEK) LIMIT 1');
		if ($res->num_rows == 0) {
			// TODO: Figure out the message
			$this->logger->info('Няма липса на грешки');
			return;
		}

		// TODO: Why only one row ?
		$row = $res->fetch_assoc();

		$this->sourceId = intval($row['sourceid']);
		$this->shortName = $row["shortname"];

		$this->logger->info('Предупреждение за ' . $this->shortName);

		$title = "Няма новини от " . $this->shortName . " от поне две седмици";
		$hash = md5($title . $this->db->time());
		$query = [];
		$query[] = [
			'title' => $title,
			'description' => null,
			'date' => Database::now(),
			'url' => $row['url'],
			'hash' => $hash,
		];
		return $query;
	}

	protected  function processItems(Array $query) {
		$this->saveItems($query);

		switch (rand(1, 4)) {
			case 1:
				$tweet = "@yurukov " . $this->shortName . " не са пускали нищо наскоро. Може би има проблем:";
				break;
			case 2:
				$tweet = "@yurukov провери дали " . $this->shortName . " не са си променили сайта, че не намирам нищо ново:";
				break;
			case 3:
				$tweet = "@yurukov от доста време няма новини от " . $this->shortName . ". Провери логовете ми за грешки.";
				break;
			default:
				$tweet = "@yurukov шефе, няма новини от " . $this->shortName . " от поне две седмици. Виж дали има проблем със сайта им:";
				break;
		}
		$this->queueTextTweet($tweet, $query[0]['url']);
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}
} 