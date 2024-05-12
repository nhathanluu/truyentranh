<?php
    class chapter_delete_model extends model{

        public function index($chapter_id){

            $this->pg_connect();

            
            $manga_id = @$this->pg_select(
                "manga_id",
                "chapters where chapter_id = " . $chapter_id
            )[0][0];

            if($manga_id){
                
                

                $this->pg_query("DELETE FROM chapters WHERE chapter_id = "  . $chapter_id);

            }else{

                error([
                    "message" => "Failed to delete"
                ]);
            }
        }

        private function del_chapter($path){

            $post = post_data(SERVER_IMG . "del_chapter.php",[
                "path" => $path
            ]);

            
            if($post["errno"] || !(int)$post["content"]){
                error([
                    "message" => "Error! Please try again"
                ]);
            }
        }
    }
?>