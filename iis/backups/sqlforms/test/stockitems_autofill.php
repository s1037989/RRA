<?php
// This script and data application were generated by AppGini 4.2 on 1/14/2009 at 11:53:44 AM
// Download AppGini for free from http://www.bigprof.com/appgini/download/

	include(dirname(__FILE__)."/defaultLang.php");
	include(dirname(__FILE__)."/language.php");
	include(dirname(__FILE__)."/lib.php");

	header("Content-type: text/javascript; charset=us-ascii");

	$mfk=$_GET['mfk'];
	$id=makeSafe($_GET['id']);

	if(!$mfk || !$id){
		die('// no js code available!');
	}

	switch($mfk){


	}

?>