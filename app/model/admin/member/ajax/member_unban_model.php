<?php
    class member_unban_model extends model{

        public function index($user_id){
            
            $rs = @$this->pg_query(
                "UPDATE 
                    \"user\" 
                SET 
                    level = 0
                WHERE 
                    user_id = " . $user_id . " AND level != 1"
            );

             myredis::global()->del("ban/{$user_id}");

            if($rs){
                
                echo json_encode([
                    "error" => 0
                ]);
            }
        }
    }
?>