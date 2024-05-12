<?php

    class update_chapter_model extends model{

        public function index(){
            
            session_write_close();

            $url  = remove_ws(@$_POST["url"]);
            $lock = (int)shell_exec("sh shell/lock.sh {$url}");

            $site = parse_url($url)["host"];

            if($site == "w11.mangafreak.net"){
                $model = "mangafreak";
            }else{
                $model = "manganelo";
            }

            if($lock){

            	error([
            		"message" => "script is running"
            	]);

            }else{

	            exec("php crawl/add_manga.php --url={$url} --site={$model} --server=blogger > /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/file.log & echo $!;");

            	success([
            		"message" => "start crawling"
            	]);
            }
        }

        private function create_process($url){

        }
    }
?>
