<?php
/*
    Running tasks
*/

class TaskManager {

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
			$taskFilePath = BASEPATH . '/' . $row["lib"] . '/tasks.php';
			if (file_exists($taskFilePath)) {
				require_once($taskFilePath);
				resetSession();
				call_user_func($row["task"]);
				if (!$force && $row["delay"] != 0) {
					if (!$session["error"] || $row["delay"] <= 4) {
						if ($row["delay"] > 24)
							$db->query("UPDATE TASK SET lastrun=date_sub(now(),interval " . rand(10, 180) . " minute) where lib='${row["lib"]}' and task='${row["task"]}' limit 1");
						else
							$db->query("UPDATE TASK SET lastrun=now() where lib='${row["lib"]}' and task='${row["task"]}' limit 1");
					} else
						$logger->error('Засякох грешка. Не маркирам като пусната задача. Ще опитам пак след малко.');
				}
			}
		}
		$res->free();
		$tool=ceil((microtime(true)-$loadStart)*1000);
		$db->query("UPDATE task_stat SET tasks=$taskCount, took=$tool WHERE tasks IS NULL LIMIT 1");
	}
}
