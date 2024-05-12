<?php
    class delete_model extends model{

        public function index(){

            $id = $_POST["id"];

            $this->pg_connect();
            $rs = @$this->pg_query(
                "DELETE FROM pat WHERE pat_id = {$id} returning img_path"
            );

            $img_path = pg_fetch_row($rs)[0];

            if($img_path){
                unlink("images_deleted/16214640667629.jpeg");
            }
        }
    }
?>