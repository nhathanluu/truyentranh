<?php
	set_include_path($_SERVER['DOCUMENT_ROOT']);

	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/init.php";
	require_once "/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/library/simple_html_dom.php";
    require_once "crawl/nettruyen.php";

	$url = "http://www.nettruyenpro.com/";
	//$url = "https://hamtruyentv.net/top-truyen.html";

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
	
	//print_r($url_arr);die;
	
	
	foreach ($url_arr as $url) {
	    if($url != 'https://hamtruyentv.net/doc-truyen/trong-sinh-do-thi-tu-tien.html') {
	        print_r($url);
            $m = new nettruyen();
            $m->manga($url);
	    }
	       
    }

?>