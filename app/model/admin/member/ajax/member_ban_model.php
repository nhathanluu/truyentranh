<?php
    class member_ban_model extends model{

        public function index($user_id){

            $rs = @$this->pg_query(
                "UPDATE 
                    \"user\" 
                SET 
                    level = 3 
                WHERE 
                    user_id = " . $user_id . " AND level != 1"
            );

            if($rs && pg_affected_rows($rs) > 0){

                myredis::global()->set("ban/{$user_id}","");
                myredis::global()->expire("ban/{$user_id}",86400);

            }else{

                error([
                    "message" => "Không thể ban tài khoản quản trị"
                ]);
            }
        }
    }
?>