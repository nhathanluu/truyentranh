<?php
    //echo dirname( dirname(__FILE__) );die;

	//set_include_path($_SERVER['DOCUMENT_ROOT']);
	set_include_path(dirname( dirname(__FILE__) ));
	
	ini_set('display_errors', 1);
    error_reporting(~0);

	require_once "crawl/nettruyen.php";

    if (!empty($_GET['url'])) {
        $val['url'] = $_GET['url'];
    } else {
        $val = getopt(null, ["url:"]);
    }
	
	$url    = @$val["url"];
	print($url);
	
	$m = new nettruyen();
	$m->manga($url);
	
?>