<?php
/*
    Running tasks
*/

function runTasks($force) {
	global $link;
	$res = $link->query("select tasktd from task_stat where tasks is null and tasktd>date_sub(now(), interval 30 minute) limit 1") or reportDBErrorAndDie();
	if ($res->num_rows>0) {
		echo "Върви друг процес.\n";
		return;
	}
	$res->free();
	$link->query("insert LOW_PRIORITY ignore into task_stat value (now(),null,null)") or reportDBErrorAndDie();

	$loadstart=microtime(true);
	$res = $link->query("select lib, task, delay from task where active=1".($force?"":" and (lastrun is null or date_add(lastrun, interval delay hour)<=date_add(now(), interval 5 minute))")." order by lib asc, priority desc limit 30") or reportDBErrorAndDie();
	$tasks=$res->num_rows;
	echo "Пускам ".$res->num_rows." задачи\n";
	while ($row=$res->fetch_assoc())
		if (file_exists($row["lib"]."/tasks.php")) {
			require_once($row["lib"]."/tasks.php");
			resetSession();
			call_user_func($row["task"]);
			if (!$force && $row["delay"]!=0) {
				if (!$session["error"] || $row["delay"]<=4) {
					if ($row["delay"]>24)
						$link->query("update task set lastrun=date_sub(now(),interval ".rand(10,180)." minute) where lib='${row["lib"]}' and task='${row["task"]}' limit 1") or reportDBErrorAndDie();
					else
						$link->query("update task set lastrun=now() where lib='${row["lib"]}' and task='${row["task"]}' limit 1") or reportDBErrorAndDie();
				} else
					echo "Засякох грешка. Не маркирам като пусната задача. Ще опитам пак след малко.\n";
			}
		}
	$res->free();
	$tool=ceil((microtime(true)-$loadstart)*1000);
	$link->query("update task_stat set tasks=$tasks, took=$tool where tasks is null limit 1") or reportDBErrorAndDie();
}
