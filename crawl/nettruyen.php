<?php

    //set_include_path($_SERVER['DOCUMENT_ROOT']);
    set_include_path(dirname( dirname(__FILE__) ));

    function t1($arr){

        $str = "";

        foreach ($arr as $value) {
            
            $str .= "{$value},";
        }

        return trim($str,",");
    }

    function t2($arr,$manga_id){

        $str = "";

        foreach ($arr as $value) {
            
            $str .= "({$manga_id},{$value}),";
        }

        return trim($str,",");
    }

    require_once "init.php";
    require_once "library/simple_html_dom.php";
    require_once "crawl/nettruyen_chapter.php";

    class nettruyen extends model{

        public function manga($url){

            $this->pg_connect();

            $rs = @$this->pg_select(
                "manga_id",
                "crawl where url = '$url'"
            )[0];

        

            if($rs){

                print "Manga crawled\n";
                print "Chapter crawling...";
                
                $this->config_crawl_chapter($url);      
                return;
            }

            $content = $this->config_curl($url);
            
              
            if($content["errno"]){

                print("Error! unvaid url \n");
                return;
            }
            
            $html    = str_get_html($content["content"]);
            $d       = $this->manga_detail($html);
    
            
            
            /// start transaction ///
            $this->pg_query("BEGIN");

            $author_ids = $this->insert_authors($d["manga_authors"]);
            $tag_ids    = $this->insert_tags($d["manga_tags"]);

            $manga_id = $this->insert_manga($d["manga_name"],$d["others_name"],$d["manga_status"],$d["manga_description"]
            );

            if($tag_ids){
                
                $mangas_tags = $this->insert_mangas_tags($manga_id,$tag_ids
                );
            }

            if($author_ids){
      
                $manga_authors = $this->insert_mangas_authors($manga_id,$author_ids
                );

            }

            $crawl = $this->insert_crawl(
                $manga_id,
                $url
            );
          
      

            if($manga_id && $crawl){
                
            
               
               $d["cover_img"] = str_replace('https', 'http', $d["cover_img"]);

                $curl_img = $this->config_curl_img($d["cover_img"]);
                //var_dump($curl_img);die;
                
                if(!$curl_img["errno"]){
                    
                    $raw_img = $curl_img["content"];

                    $info_img  = getimagesizefromstring($raw_img);
                    
                    if(!is_array($info_img)){
                        die(
                            "url is not an image\n" 
                        );
                    }

                    $width  = $info_img[0];
                    $height = $info_img[1];

                    $ext      = explode("/",$curl_img["content_type"])[1]; 
                    $url_name = "{$width}x{$height}-" . url_name_replace($d["manga_name"]) . "." . $ext;

                    $dir = ROOT . "img/{$manga_id}/";

                    mkdir($dir,0755);

                    file_put_contents($dir .  $url_name ,$raw_img);
                    chmod($dir . $url_name , 0644);

                    $this->pg_query(
                        "UPDATE 
                            manga 
                        SET 
                            manga_cover_img = '{$url_name}'
                        WHERE 
                            manga_id = {$manga_id}");

                    $this->pg_query("COMMIT");
                    
                    $this->config_crawl_chapter($url);

                }else{

                    print "Failed to crawl cover img!\n";
                }

            }else{

                $this->pg_query("ROLLBACK") or die("Transaction rollback failed\n");
            }
        }

        protected function config_crawl_chapter($url){
            $m = new nettruyen_chapter();
            $m->query($url);
        }

        protected function config_curl_img($cover_img){
            return get_web_page($cover_img,[
                CURLOPT_REFERER =>  "https://manganelo.com"
            ]);
        }

        protected function manga_detail($html){

            $manga_name = $html->find("article#item-detail > h1")[0]->innertext;
            $cover_img  = $html->find("div.col-image > img")[0]->src;

            $cover_img = preg_match('/^\/\//', $cover_img) ? str_replace("//","",$cover_img) : $cover_img;



            $manga_description = $html->find("div.detail-content > p")[0]->innertext;
            $others_name = @$html->find("li.othername > h2")[0]->innertext;
            $manga_status = trim($html->find("li.status > p[2]")[0]->innertext) == 'Đang tiến hành' ? "f" : "t";

            $manga_tags    = [];
            $manga_authors = [];

            $html_author = $html->find("li.author > p[2] a");
            $html_tag    = $html->find("li.kind > p[2] a");

            foreach ($html_author as  $value) {
                array_push($manga_authors, strtolower($value->innertext));
            }

            foreach ($html_tag as  $value) {
                array_push($manga_tags, strtolower($value->innertext));
            }

            return [
                "manga_name"        => $manga_name,
                "others_name"       => $others_name,
                "manga_status"      => $manga_status,
                "manga_tags"        => $manga_tags,
                "manga_authors"     => $manga_authors,
                "cover_img"         => $cover_img,
                "manga_description" => $manga_description
            ];
        }

        private function insert_authors($authors){

            if(!$authors){
                return;
            }

            $query = "INSERT INTO authors(author_name) SELECT t.author_name FROM (SELECT '" . pg_replace($authors[0]) . "' as author_name union all";

            for ($i = 1 ; $i < count($authors); $i++){ 
                
                $query .= " SELECT '" . pg_replace($authors[$i]) . "' union all ";
            }

            $query = trim($query," union all ") . ") as t WHERE NOT EXISTS (SELECT 1 FROM authors p WHERE p.author_name = t.author_name)";


            $rs1 = $this->pg_query($query);

            if($rs1) :

                $in = "";

                foreach ($authors as $author){

                    $in .= "'" . pg_replace("{$author}") . "',";
                }

                $in = trim($in,",");

                $rs = $this->pg_select(
                    "author_id",
                    "authors where author_name IN (" .  $in . ")"
                );

                return $rs;

            else :

                return 0;

            endif;
        }

        private function insert_tags($tags){

            if(!$tags){
                return;
            }

            $now = date("Y-m-d H:i:s");

            $query = 
                "INSERT INTO tags(
                    tag_name,
                    tag_description,
                    t_date_created,
                    t_date_modified
                )
                SELECT 
                    t.tag_name,
                    t.tag_description,
                    t.date_created,
                    t.date_modified
                FROM (
                    SELECT 
                        '" . pg_replace($tags[0]) . "' as tag_name,
                        '" . convert_to_search_str($tags[0]) ."' as tag_description,
                        '{$now}'::timestamp as date_created,
                        '{$now}'::timestamp as date_modified union all";

            for ($i = 1 ; $i < count($tags); $i++){ 
                
                $query .= " SELECT '" . pg_replace($tags[$i]) . "','"  . convert_to_search_str($tags[$i]) .  "','{$now}','{$now}' union all ";
            }

            $query = trim($query," union all ") . ") as t WHERE NOT EXISTS (SELECT 1 FROM tags p WHERE p.tag_name = t.tag_name)";

            $rs1 = $this->pg_query($query);

            if($rs1) :

                $in = "";

                foreach ($tags as $tag){

                    $in .= "'" . pg_replace("{$tag}") . "',";
                }

                $in = trim($in,",");

                $rs = $this->pg_select(
                    "tag_id",
                    "tags where tag_name IN (" .  $in . ")"
                );

                return $rs;

            else :

                return 0;

            endif;
        }

        protected function config_curl($url){
            return get_web_page($url,[
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT_MS     => 5000
            ]);
        }

        function convert_vi_to_en($str) {
            $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
            $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
            $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
            $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
            $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
            $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
            $str = preg_replace("/(đ)/", "d", $str);
            $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
            $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
            $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
            $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
            $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
            $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
            $str = preg_replace("/(Đ)/", "D", $str);
            //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
            return strtoupper($str);
        }

        private function insert_manga(
            $manga_name,
            $others_name,
            $manga_status,
            $manga_description
        ){

            $rs = $this->pg_query(
                "INSERT INTO manga(
                    manga_name,
                    manga_others_name,
                    search_latin,
                    manga_status,
                    manga_description,
                    manga_date_published,
                    manga_updated
                )
                VALUES(
                    '" . pg_replace(htmlspecialchars_decode($manga_name,ENT_QUOTES)) . "',
                    '" . pg_replace(htmlspecialchars_decode($others_name,ENT_QUOTES)) . "',
                    '" . $this->convert_vi_to_en(pg_replace(htmlspecialchars_decode($manga_name,ENT_QUOTES))) . "',
                    '{$manga_status}',
                    '". pg_replace(htmlspecialchars_decode($manga_description,ENT_QUOTES)) ."',
                    '" . date("Y-m-d H:i:s") ."',
                    '" . date("Y-m-d H:i:s") ."'
                ) RETURNING manga_id"
            );

            if($rs) :
                
                while ($row = pg_fetch_row($rs)){
                    return $row[0];
                }

            endif;

            return 0;
        }

        private function insert_mangas_tags(
            $manga_id,
            $tags
        ){

            return $this->pg_query("
                INSERT INTO mangas_tags VALUES " . t2(make_row($tags,0),$manga_id)
            );
        }

        private function insert_mangas_authors(
            $manga_id,
            $authors
        ){
            // print("
            // INSERT INTO mangas_authors VALUES " . t2(make_row($authors,0),$manga_id));die;

            return $this->pg_query("
                INSERT INTO mangas_authors VALUES " . t2(make_row($authors,0),$manga_id)
            );
        }

        private function insert_crawl($manga_id,$url){
            return $this->pg_query(
                "INSERT INTO crawl VALUES({$manga_id},'{$url}')"
            );
        }

        private function next_id(){

            $rs = $this->pg_query("select nextval(pg_get_serial_sequence('manga', 'manga_id')) as new_id;");

            while ($row = pg_fetch_row($rs)){
                return $row[0];
            }
        }


        public function doidau(){
            $this->pg_connect();
            $rs = $this->pg_select(
                "manga_id, manga_name",
                "manga"
            );

            return $rs;
        }

        public function update_name($name, $manga_id){
            $this->pg_connect();
            $rs = $this->pg_select(
                "manga_id, manga_name",
                "manga"
            );

            @$this->pg_query(
                "UPDATE 
                    \"manga\" 
                SET 
                    search_latin = '".strtolower($this->convert_vi_to_en($name))."'
                WHERE 
                manga_id = " . $manga_id . " "
            );

            return true;
        }


    }
?>

