<?php
    class add_tag_model extends model{

        public function index($manga_id){

            $tag_name = $this->tag_name($_POST["tag_name"]);

            $this->pg_connect();

            $this->pg_query("BEGIN");

            $tag_id = @$this->pg_select(
                "tag_id",
                "tags where tag_name = '" . $tag_name . "'"
            )[0][0];

            if(!$tag_id){

                $tag_id = $this->insert_new_tag($tag_name);

                require "genre.php";
            }

            $rs2 = $this->insert_mangas_tags($manga_id,$tag_id);    

            if($tag_id && $rs2){

                $this->pg_query("COMMIT");

                echo json_encode([
                    "error"   => 0,
                    "tag_id"  => $tag_id,
                    "message" => "added successfully"
                ]);
            }else{

                $this->pg_query("ROLLBACK");

                error([
                    "message" => "Error!"
                ]);
            }
        }

        private function tag_name($tag_name){

            $tn = @pg_replace(remove_ws(strtolower($tag_name)));

            if (strlen($tn) <= 1){

                error([
                    "message" => "Minimum 2 characters"
                ]);
            }

            return $tn;
        }

        private function insert_new_tag($tag_name){

            $now = date("Y-m-d H:i:s");

            $rs = $this->pg_query(
                "INSERT INTO 
                    tags(
                        tag_name,
                        tag_search,
                        t_date_created,
                        t_date_modified
                    )
                VALUES (
                    '{$tag_name}',
                    '" . convert_to_search_str($tag_name) .  "',
                    '{$now}',
                    '{$now}'
                ) RETURNING tag_id"
            );

            return pg_fetch_row($rs)[0];
        }

        private function insert_mangas_tags($manga_id,$tag_id){

            return @$this->pg_query(
                "INSERT INTO 
                    mangas_tags (
                        manga_id,
                        tag_id
                    )
                VALUES (
                    {$manga_id},
                    {$tag_id}
                )"
            );
        }
    }
?>