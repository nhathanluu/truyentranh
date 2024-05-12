<?php 

set_include_path($_SERVER['DOCUMENT_ROOT']);
ini_set('display_errors', 1);
error_reporting(~0);

require_once "crawl/nettruyen.php";

$urls    = [
    'http://www.nettruyenco.com/truyen-tranh/thich-khach-tin-dieu-343400'
];

foreach ($urls as $url) {
    $m = new nettruyen();
    $m->manga($url);   
}