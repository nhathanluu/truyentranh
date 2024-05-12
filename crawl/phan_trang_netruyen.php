<?php
	set_include_path($_SERVER['DOCUMENT_ROOT']);

	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/init.php";
	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/library/simple_html_dom.php";
    
	

	print "Start crawling...\n";
	
	for ($i = 101; $i <= 106; $i++) {
	    $url = "http://www.nettruyenvip.com/?page=".$i;
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
	}
	
	echo '<pre>';
    var_dump($url_arr);die;

	
	
	foreach ($url_arr as $url){
	    
	    require_once "crawl/nettruyen.php";
	    $m = new nettruyen();
        $m->manga($url);
	}

?>