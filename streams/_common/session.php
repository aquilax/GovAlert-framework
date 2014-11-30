<?php

/*
    Session handling
*/

function setSession($sourceid,$category) {
	global $session;
	$session["sourceid"]=$sourceid;
	$session["category"]=$category;
	$session["error"]=false;
}

function resetSession() {
	global $session;
	$session["sourceid"]=null;
	$session["category"]=null;
	$session["error"]=false;
}

function checkSession() {
	global $session;
	if ($session["sourceid"]==null) {
		reportError("Не е заредена сесията");
		return false;
	}
	return true;
}
