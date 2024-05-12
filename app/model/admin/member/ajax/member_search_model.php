<?php
    class member_search_model extends model{

        public function index($user_id){

            $query = @pg_replace($_GET["query"]);

            $rs = $this->pg_select_obj(
                "user_id,account,nickname,level,avatar",
                "\"user\" 
                    WHERE 
                        account
                            ILIKE '%" . $query . "%'"
            );

            echo json_encode($rs);      
        }
    }
?>