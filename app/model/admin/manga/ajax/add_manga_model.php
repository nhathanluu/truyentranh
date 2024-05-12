<?php
    class add_manga_model extends model{

        public function index(){

            $cover_img         = $this->cover_img($_POST["cover_img"]);
            $manga_name        = $this->manga_name($_POST["manga_name"]);
            $manga_description = $_POST["manga_description"];
            $status            = $this->manga_status($_POST["status"]);
            $others_name       = $_POST["others_name"];

            $this->pg_connect();

            $this->pg_query("BEGIN");

            $authors = $this->manga_authors(array_filter(explode(",",$_POST["manga_authors"])));
            $tags    = $this->manga_tags(array_filter(explode(",",$_POST["manga_tags"])));

            $manga_cover_img = tmp_name() . url_name_replace($manga_name) . "." . $cover_img["ext"];

            $rs = $this->pg_query(
                "INSERT INTO 
                    manga 
                    (
                        manga_name,
                        manga_others_name,
                        manga_search,
                        manga_description,
                        manga_cover_img,
                        manga_status,
                        manga_updated
                    )
                VALUES 
                    (
                        '{$manga_name}',
                        '{$others_name}',
                        '" . convert_to_search_str($manga_name . " " . $others_name) . "',
                        '{$manga_description}',
                        '{$manga_cover_img}',
                        {$status},
                        NOW()
                    )
                RETURNING manga_id"
            );

            $manga_id = pg_fetch_row($rs)[0];

            if($tags){
                $rs2 = $this->pg_query(
                    "INSERT INTO 
                        mangas_tags 
                    VALUES " . str_replace("m",$manga_id,$tags["insert"])
                );
            }

            if($authors){

                $rs3 = $this->pg_query(
                    "INSERT INTO 
                        mangas_authors 
                    VALUES " . str_replace("m",$manga_id,$authors["insert"])
                );
            }

            if($rs){

                $img_path = "img/{$manga_id}";

                if(!is_dir('img')){
                    error([
                        "message" => "dir doesn't exist"
                    ]);
                }

                mkdir($img_path,0755);
                file_put_contents($img_path . "/" . $manga_cover_img ,$cover_img["raw"]);

               $this->pg_query("COMMIT");
                
                echo json_encode([
                    "error"    => 0,
                    "message"  => "Thêm thành công!",
                    "manga_id" => $manga_id
                ]);

            }else{

                error([
                    "message" => "Error!"
                ]);
            }
        }

        private function cover_img($cover_img){

            if(!file_exists("tmp/" . $cover_img)){

                error([
                    "message" => "Hãy chọn bìa truyện"
                ]);
            }

            return [
                "raw" => file_get_contents("tmp/" . $cover_img),
                "ext" => explode(".",$cover_img)[1]
            ];
        }

        private function manga_name(&$manga_name){

            if(remove_ws(strlen($manga_name)) <= 2){

                error([
                    "message" => "Tên truyện ít nhất 2 ký tự"
                ]);
            }

            return $manga_name;
        }

        private function manga_status($status){


            if($status == 0){

                $st = "'f'";

            }else{

                $st = "'t'";
            }

            return $st;
        }

        private function manga_tags($tags){
            
            if(!$tags){
                return;
            }

            $now = date("Y-m-d H:i:s");

            $query = 
                "INSERT INTO tags(
                    tag_name,
                    tag_search,
                    t_date_created,
                    t_date_modified
                )
                SELECT 
                    t.tag_name,
                    t.tag_search,
                    t.date_created,
                    t.date_modified
                FROM (
                    SELECT 
                        '" . pg_replace($tags[0]) . "' as tag_name,
                        '" . convert_to_search_str($tags[0]) ."' as tag_search,
                        '{$now}'::timestamp as date_created,
                        '{$now}'::timestamp as date_modified union all";

            for ($i = 1 ; $i < count($tags); $i++){ 
                
                $query .= " SELECT '" . pg_replace($tags[$i]) . "','"  . convert_to_search_str($tags[$i]) .  "','{$now}','{$now}' union all ";
            }

            $query = trim($query," union all ") . ") as t WHERE NOT EXISTS (SELECT 1 FROM tags p WHERE p.tag_name = t.tag_name)";

            $rs1 = @$this->pg_query($query);

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

                $insert = '';

                foreach ($rs as $value){
                    $insert .= "(m,{$value[0]}),";
                }

                return [
                    "insert" => trim($insert,',')
                ];

            else :

                return 0;

            endif;
        }

        private function manga_authors($authors){

            if(!$authors){
                return;
            }

            $query = "INSERT INTO authors(author_name) SELECT t.author_name FROM (SELECT '" . pg_replace($authors[0]) . "' as author_name union all";

            for ($i = 1 ; $i < count($authors); $i++){ 
                
                if(strlen(remove_ws($authors[$i])) == 0){

                    continue;
                }

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

                $insert = '';

                foreach ($rs as $value){
                    $insert .= "(m,{$value[0]}),";
                }

                return [
                    "insert" => trim($insert,',')
                ];

            else :

                return 0;

            endif;
        }
    }
?>