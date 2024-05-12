<?php
	set_include_path($_SERVER['DOCUMENT_ROOT']);

	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/init.php";
	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/library/simple_html_dom.php";
    
	$url = "http://www.nettruyenvip.com/?page=442";

	print "Start crawling...\n";

	$curl = get_web_page($url);

	if($curl["errno"]){
		die("->Error : Could not crawl {$url}\n");
	}

	$html = str_get_html($curl["content"]);

    $p = $html->find("h3 > a.jtip");

	$url_arr = [];
	$in = "";
	$i = 0;

	foreach ($p as $v){

		$in .= "'{$v->href}',";
		$url_arr[$i] = $v->href;

		$i++;
	}
	
// 	echo '<pre>';
// 	var_dump($url_arr);die;

	$in = trim($in,",");

	$pg = new model();
	$pg->pg_connect();

	$rs = $pg->pg_select(
		"url",
		"crawl
			WHERE
				url IN ({$in})"
	);
	
	foreach ($url_arr as $url){
	    
	    require_once "crawl/nettruyen.php";
	    $m = new nettruyen();
        $m->manga($url);
	}

	function start_to_crawl($url){

		$url = remove_ws($url);

		if($url == ""){
			print "Error Unvaid url!";
		}

        $lock = (int)exec("sh /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/shell/lock.sh {$url}");

        $lock = false;
        if($lock){

        	return;

        }else{

            shell_exec("php /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/crawl/add_manga.php --url={$url} --site=manganelo > /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/cron.log");
        }
	}
?>