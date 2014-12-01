<?php
/*
    Running tasks
*/

class TaskManager {

	// TODO: Remove me when all tasks are migrated to classRun
	static function legacyRun($lib, $task) {
		$taskFilePath = BASEPATH . '/' . $lib . '/tasks.php';
		if (file_exists($taskFilePath)) {
			require_once($taskFilePath);
			resetSession();
			call_user_func($task);
			return true;
		}
		return false;
	}

	static function classRun($lib, $method, Database $db, Logger $logger) {
		$className = ucfirst($lib);
		$taskClassFilePath = BASEPATH . '/_tasks/' . $className . '.php';
		echo $taskClassFilePath.PHP_EOL;
		if (file_exists($taskClassFilePath)) {
			require_once($taskClassFilePath);
			resetSession();
			$task = new $className($db, $logger);
			$task->{$method}();
			return true;
		}
		return false;
	}

	static function runTasks(Database $db, Logger $logger, $force) {
		global $session;
		$res = $db->query('SELECT tasktd FROM task_stat WHERE tasks IS NULL AND tasktd > date_sub(now(), INTERVAL 30 MINUTE) LIMIT 1');
		if ($res->num_rows > 0) {
			$logger->info('Върви друг процес');
			return;
		}
		$res->free();
		$db->query('INSERT LOW_PRIORITY ignore INTO task_stat VALUE (now(),null,null)');

		$loadStart=microtime(true);
		$res = $db->query("SELECT lib, task, delay FROM task WHERE active=1".($force?"":" and (lastrun is null or date_add(lastrun, interval delay hour)<=date_add(now(), interval 5 minute))")." order by lib asc, priority desc limit 30");
		$taskCount = $res->num_rows;
		$logger->info('Пускам ' . $taskCount . ' задачи');
		while ($row = $res->fetch_assoc()) {
			$run = self::legacyRun($row['lib'], $row['task']);
			if (!$run) {
				$run = self::classRun($row['lib'], $row['task'], $db, $logger);
			}
			if ($run && !$force && $row["delay"] != 0) {
				if (!$session["error"] || $row["delay"] <= 4) {
					if ($row["delay"] > 24)
						$db->query("UPDATE TASK SET lastrun=date_sub(now(),interval " . rand(10, 180) . " minute) where lib='${row["lib"]}' and task='${row["task"]}' limit 1");
					else
						$db->query("UPDATE TASK SET lastrun=now() where lib='${row["lib"]}' and task='${row["task"]}' limit 1");
				} else
					$logger->error('Засякох грешка. Не маркирам като пусната задача. Ще опитам пак след малко.');
			}
		}
		$res->free();
		$tool=ceil((microtime(true)-$loadStart)*1000);
		$db->query("UPDATE task_stat SET tasks=$taskCount, took=$tool WHERE tasks IS NULL LIMIT 1");
	}
}
