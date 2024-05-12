<?php
	
	ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    session_start();

	// local path: /www/wwwroot/truyen.thietkewebtheomau.com
	
	const 
		HOST 		  = "truyentranh-postgres",
		DATABASE_NAME = "postgres", // Tên database của bạn
		DATABASE_USER = "postgres", 
		DATABASE_PASS = "postgres", // Mật khẩu database
		ROOT 		  = "/var/www/", // Đường dẫn thư mục website
		SITE_URL 	  = "https://gautruc.net/", // Tên website : Lưu ý Có dấu / vào cuối đuôi tên miên
		ICON 		  = SITE_URL . "index.png",
		SITE_NAME	  = "GauTruc.Net"; 

	const 
		LIB 		  = ROOT . "library/",
		CONTROLLER 	  = ROOT . "app/controller/",
		VIEW 		  = ROOT . "app/view/",
		MODEL 		  = ROOT . "app/model/",
		TH_PUBLIC     = SITE_URL . "public/";

	require_once(LIB . "functions.php");
	require_once(ROOT . "app/application.php");
?>