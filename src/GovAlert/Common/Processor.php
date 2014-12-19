<?php

namespace GovAlert\Common;


class Processor {

	public function __construct(Database $db, Logger $logger, $debug = false) {
		$this->db = $db;
		$this->logger = $logger;
		$this->debug = $debug;
	}


	public function saveItems(Array $items, \GovAlert\Tasks\Task $task)
	{
		if (!$items || count($items) == 0) {
			return false;
		}

		$this->logger->info('Запазвам ' . count($items) . '... ');

		$hashes = $this->getHashes(array_column($items, 'hash'));

		$query = array();
		foreach ($items as $item) {
			if (in_array($item['hash'], $hashes)) {
				continue;
			}
			$query[] = [
				'title' => $item['title'],
				'description' => $item['description'],
				'sourceid' => $task->getSourceId(),
				'category' => $task->getCategoryId(),
				'pubts' => $item['date'],
				'readts' => Database::now(),
				'url' => $item['url'],
				'hash' => $item['hash'],
				'media' => isset($item['media']) ? $item['media'] : null,
			];
		}

		$this->logger->info('...oт тях ' . count($query) . ' са нови... ');
		$changed = [];

		if ($query) {
			$query = array_reverse($query);
			foreach ($query as $row) {
				$res = $this->saveItem($row);
				if ($res) {
					$changed[] = $res;
				}
			}
		}
		$this->logger->info('записани ' . count($changed));
		return $changed;
	}

	private function getHashes(Array $hashes)
	{
		array_walk($hashes, function (&$hash) {
			$hash = Database::quote($hash);
		});
		$res = $this->db->query('SELECT hash FROM item WHERE hash IN (' . implode(', ', $hashes) . ') limit ' . count($hashes));
		$hashes = [];
		while ($row = $res->fetch_array()) {
			$hashes[] = $row[0];
		}
		$res->free();
		return $hashes;
	}

	private function saveItem(Array $item)
	{
		$itemId = 0;
		$media = false;

		// Remove media first
		if (array_key_exists('media', $item)) {
			if (is_array($item['media'])) {
				$media = $item['media'];
			}
			unset($item['media']);
		}

		$this->db->insert('item', $item);
		if ($this->db->affected_rows > 0) {
			$itemId = $this->db->insert_id;
			if ($media) {
				$this->saveMedia($media, $itemId);
			}
		}
		return $itemId;
	}

	private function saveMedia($media, $itemId)
	{
		$rows = [];
		foreach ($media as $mediaKey => $mediaValue) {
			if (!$mediaValue[0] || $mediaValue[0] == null)
				continue;
			if (is_array($mediaValue[0])) {
				foreach ($mediaValue as $mediaValueItem) {
					if (!$mediaValueItem[0] || $mediaValueItem[0] == null)
						continue;
					$rows[] = [
						'itemid' => $itemId,
						'type' => $mediaKey,
						'value' => $mediaValueItem[0],
						'description' => $mediaValueItem[0]
					];
				}
			} else {
				$rows[] = [
					'itemid' => $itemId,
					'type' => $mediaKey,
					'value' => $mediaValue[0],
					'description' => $mediaValue[1]
				];
			}
		}
		$this->db->batchInsert('item_media', $rows, 'LOW_PRIORITY IGNORE');
	}

	function checkHash($hash)
	{
		$res = $this->db->query("SELECT hash FROM item WHERE hash='$hash' LIMIT 1");
		return $res->num_rows == 0;
	}

	function checkTitle($title, $sourceId) {
		$res = $this->db->query("SELECT hash FROM item WHERE title='$title' AND sourceid=" . $sourceId . " LIMIT 1");
		return $res->num_rows == 0;
	}
}
