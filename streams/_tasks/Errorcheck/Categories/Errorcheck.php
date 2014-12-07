<?php

class Errorcheck extends Task
{
	protected $sourceId = 0;
	protected $sourceName = 'липса на новини и възможни грешки';
	protected $categoryId = 0;
	protected $categoryName = '';
	protected $categoryURL = false;

	function execute($html)
	{
		$res = $this->db->query('select * from (select s.sourceid sourceid, s.shortname shortname, s.url url, max(i.readts) lastread, count(i.itemid) items from source s left outer join item i on s.sourceid=i.sourceid group by s.name order by max(readts) asc) a where a.lastread<subdate(now(), interval 2 week) limit 1');
		if ($res->num_rows == 0) {
			// TODO: Figure out the message
			$this->logger->info('Няма липса на грешки');
			return;
		}

		// TODO: Why only one row ?
		$row = $res->fetch_assoc();

		$this->sourceId = intval($row['sourceid']);

		$this->logger->info('Предупреждение за ' . $row["shortname"]);

		$title = "Няма новини от " . $row["shortname"] . " от поне две седмици";
		$hash = md5($title . time());
		$query = [];
		$query[] = [
			'title' => $title,
			'description' => null,
			'date' => Utils::Now(),
			'url' => $row['url'],
			'hash' => $hash,
		];
		$this->saveItems($query);

		switch (rand(1, 4)) {
			case 1:
				$tweet = "@yurukov " . $row["shortname"] . " не са пускали нищо наскоро. Може би има проблем:";
				break;
			case 2:
				$tweet = "@yurukov провери дали " . $row["shortname"] . " не са си променили сайта, че не намирам нищо ново:";
				break;
			case 3:
				$tweet = "@yurukov от доста време няма новини от " . $row["shortname"] . ". Провери логовете ми за грешки.";
				break;
			default:
				$tweet = "@yurukov шефе, няма новини от " . $row["shortname"] . " от поне две седмици. Виж дали има проблем със сайта им:";
				break;
		}
		$this->queueTextTweet($tweet, $row["url"]);
	}

	protected function loader($categoryId, $categoryURL)
	{
		return 'placeholder';
	}
} 