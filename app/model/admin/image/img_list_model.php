<?php
    class img_list_model extends model{

        public function index(){

            $this->pg_connect();
            $rs = $this->pg_select_obj(
                "pat_id,img_path",
                "pat"
            );

            return $rs;
        }
    }
?>