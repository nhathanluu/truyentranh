<?php
    class update_all_chapters_model extends model{

        public function index($manga_id){
            $this->pg_connect();

            $url = $this->pg_select(
                "url",
                "crawl
                    WHERE manga_id = {$manga_id}"
            )[0][0];

            session_write_close();

            $lock = (int)shell_exec("sh shell/lock.sh {$url}");

            $site = parse_url($url)["host"];

            $chapters = @$_POST["chapters"];

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

                 exec("php crawl/add_manga.php --url={$url} --site={$model} --type=update --chapters={$chapters} > /www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/update.log & echo $!;");

                success([
                    "message" => "start crawling"
                ]);
            }
        }
    }
?>