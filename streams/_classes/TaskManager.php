<?php

/*
    Running tasks
*/

class TaskManager
{

	private $db;
	private $logger;

	public function __construct(Database $db, Logger $logger)
	{
		$this->db = $db;
		$this->logger = $logger;
	}

	private function classRun($lib, $method)
	{
		$className = ucfirst($lib);
		$taskClassFilePath = BASEPATH . '/_tasks/' . $className . '.php';
		echo $taskClassFilePath . PHP_EOL;
		if (file_exists($taskClassFilePath)) {
			require_once($taskClassFilePath);
			$task = new $className($this->db, $this->logger);
			$task->resetSession();
			$task->{$method}();
			return true;
		}
		return false;
	}

	public function runTask($lib, $task, $delay, $force)
	{
		global $session;
		$run = self::legacyRun($lib, $task);
		if (!$run) {
			$run = self::classRun($lib, $task);
		}
		if ($run && !$force && $delay != 0) {
			if (!$session["error"] || $delay <= 4) {
				if ($delay > 24) {
					$this->db->query("UPDATE task SET lastrun=date_sub(now(),interval " . rand(10, 180) . " minute) where lib='${lib}' and task='${task}' limit 1");
				} else {
					$this->db->query("UPDATE task SET lastrun=now() where lib='${lib}' and task='${task}' limit 1");
				}
			} else {
				$this->logger->error('Засякох грешка. Не маркирам като пусната задача. Ще опитам пак след малко.');
			}
		}
	}

	public function runTasks($force)
	{
		$res = $this->db->query('SELECT tasktd FROM task_stat WHERE tasks IS NULL AND tasktd > date_sub(now(), INTERVAL 30 MINUTE) LIMIT 1');
		if ($res->num_rows > 0) {
			$this->logger->info('Върви друг процес');
			return;
		}
		$res->free();
		$this->db->query('INSERT LOW_PRIORITY ignore INTO task_stat VALUE (now(),null,null)');

		$loadStart = microtime(true);
		$res = $this->db->query("SELECT lib, task, delay FROM task WHERE active=1" . ($force ? "" : " and (lastrun is null or date_add(lastrun, interval delay hour)<=date_add(now(), interval 5 minute))") . " order by lib asc, priority desc limit 30");
		$taskCount = $res->num_rows;
		$this->logger->info('Пускам ' . $taskCount . ' задачи');
		while ($row = $res->fetch_assoc()) {
			$this->runTask($row['lib'], $row['task'], $row['delay'], $force);
		}
		$res->free();
		$took = ceil((microtime(true) - $loadStart) * 1000);
		$this->db->query("UPDATE task_stat SET tasks=$taskCount, took=$took WHERE tasks IS NULL LIMIT 1");
	}
}
