<?php
    // require "callback.php";

    class drop_manga_model extends model{

        public function index($manga_id){

            $this->pg_connect();

            $this->pg_query("BEGIN");

            $manga_id = @$this->pg_select(
                "manga_id",
                "manga where manga_id = " . $manga_id
            )[0][0];

            if (!$manga_id){

                error([
                    "message" => "Manga doesn't exsist"
                ]);
            }

            $rs1 = $this->pg_query(
                "DELETE FROM mangas_authors WHERE manga_id = {$manga_id};
                DELETE FROM mangas_tags WHERE manga_id = {$manga_id};
                DELETE FROM chapters WHERE manga_id = {$manga_id};
                DELETE FROM crawl WHERE manga_id = {$manga_id};
                DELETE FROM favorites WHERE manga_id = {$manga_id};
                DELETE FROM comments WHERE manga_id = {$manga_id};
                DELETE FROM pin WHERE p_manga_id = {$manga_id};
                DELETE FROM manga WHERE manga_id = {$manga_id}"
            );

            if($rs1){

                $path_cover_img = "img/" . $manga_id;

                rrmdir($path_cover_img);

                $this->pg_query("COMMIT");

                success([
                    "message" => "Successfully deleted",
                ]);

            }else{

                error([
                    "message" => "failed to delete"
                ]);
            }
        }

        private function drop_manga($manga_id){

            $post = post_data(SERVER_IMG . "del_chapter.php",[
                "manga_id" => $manga_id
            ]);

            
            if($post["errno"]){
                error([
                    "message" => "Error! Please try again"
                ]);
            }
        }
    }
?>