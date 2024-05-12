<?php

    class add_manga_model extends model{

        public function index(){

            $url  = remove_ws(@$_POST["url"]);
            $lock = (int)shell_exec("sh shell/lock.sh {$url}");

            $site = parse_url($url)["host"];

            if($lock){

            	error([
            		"message" => "script is running"
            	]);

            }else{

	            //exec("php crawl/add_manga.php --url={$url}  > " . $_SERVER['DOCUMENT_ROOT'] . "/file.log & echo $!;");
                require_once "crawl/nettruyen.php";

                $m = new nettruyen();
                $m->manga($url);

            	success([
            		"message" => "start crawling"
            	]);
            }
        }

        private function create_process($url){

        }
    }
?>
