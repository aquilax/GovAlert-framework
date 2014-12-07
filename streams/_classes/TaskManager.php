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

	/**
	 * @param string $lib
	 * @param string $method
	 * @return bool|Task
	 */
	private function classLoader($lib, $method)
	{
		$baseClassName = ucfirst($lib);
		$className = ucfirst($method);
		$baseClassFilePath = BASEPATH . '/_tasks/' . $baseClassName . '/' . $baseClassName . '.php';
		$taskClassFilePath = BASEPATH . '/_tasks/' . $baseClassName . '/Categories/' . $className . '.php';
		if (file_exists($baseClassFilePath) && file_exists($taskClassFilePath)) {
			require_once($baseClassFilePath);
			require_once($taskClassFilePath);
			$this->logger->debug('Loaded class from: ' . $taskClassFilePath);
			return new $className($this->db, $this->logger);
		}
		throw new Exception('Task not ' . $method . 'found');
	}

	public function runTask($lib, $task, $delay, $force)
	{
		$myTask = $this->classLoader($lib, $task);
		if ($myTask) {
			$myTask->run();
			if (!$force && $delay != 0) {
				if (!$myTask->getError() || $delay <= 4) {
					if ($delay > 24) {
						$this->db->query("UPDATE task SET lastrun=date_sub(now(),interval " . rand(10, 180) . " minute) where lib='${lib}' and task='${task}' limit 1");
					} else {
						$this->db->query("UPDATE task SET lastrun=now() where lib='${lib}' and task='${task}' limit 1");
					}
				} else {
					$this->logger->error('Засякох грешка. Не маркирам като пусната задача. Ще опитам пак след малко.');
				}
			}
		} else {
			throw new Exception('Task not found lib: ' . $lib . ', task: ' . $task);
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
		$res = $this->db->query("SELECT lib, task, delay FROM task WHERE active=1" . ($force ? "" : " AND (lastrun IS NULL OR date_add(lastrun, INTERVAL delay HOUR)<=date_add(now(), INTERVAL 5 MINUTE))") . " ORDER BY lib ASC, priority DESC LIMIT " . Config::get('tasksPerRun'));
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
