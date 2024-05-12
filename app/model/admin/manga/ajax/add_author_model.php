<?php
    class add_author_model extends model{

        public function index($manga_id){

            $author_name = $this->author_name($_POST["author_name"]);

            $this->pg_connect();

            $this->pg_query("BEGIN");

            $author_id = @$this->pg_select(
                "author_id",
                "authors 
                    WHERE 
                author_name = '" . $author_name . "'"
            )[0][0];

            if(!$author_id){

                $author_id = $this->insert_new_author($author_name);
            }

            $rs2 = $this->insert_mangas_author($manga_id,$author_id);    

            if($author_id && $rs2){

                $this->pg_query("COMMIT");

                echo json_encode([
                    "error"   => 0,
                    "tag_id"  => $author_id,
                    "message" => "added successfully"
                ]);
                
            }else{

                $this->pg_query("ROLLBACK");

                error([
                    "message" => "Error!"
                ]);
            }
        }

        private function author_name($author_name){

            $tn = @pg_replace(remove_ws(strtolower($author_name)));

            if (strlen($tn) <= 1){

                error([
                    "message" => "Minimum 2 characters"
                ]);
            }

            return $tn;
        }

        private function insert_new_author($author_name){

            $rs = $this->pg_query(
                "INSERT INTO 
                    authors(author_name) 
                VALUES (
                    '{$author_name}'
                ) RETURNING author_id"
            );

            return pg_fetch_row($rs)[0];
        }

        private function insert_mangas_author($manga_id,$author_id){

            return @$this->pg_query(
                "INSERT INTO 
                    mangas_authors (
                        manga_id,
                        author_id
                    )
                VALUES (
                    {$manga_id},
                    {$author_id}
                )"
            );
        }
    }
?>