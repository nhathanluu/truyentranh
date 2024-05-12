<?php

    require_once LIB . "user.php";

    class change_password_model extends model{

        public function index(){

            $current_pw = @$_POST["cur_passwrod"];
            $new_pw     = @$_POST["new_password"];
            $confirm_pw = @$_POST["confirm_password"];

            $data = @$this->pg_select_obj(
                "user_id,account,password,codesecurity,level,nickname,avatar",
                "\"user\"",
                "user_id = '" . $_SESSION["user"]["id"] . "'"
            )[0];

            if(!$data){
                error([
                    "message" => "Your account has been deleted"
                ]);
            }

            $user_pw      = $data["password"];
            $codesecurity = $data["codesecurity"];

            $hash_pass = md5(static_salt . $current_pw . trim($codesecurity));

            if($hash_pass != trim($user_pw)){

                error([
                    "message" => "Wrong current password!"
                ]);
            }

            if($new_pw != $confirm_pw){
                error([
                    "message" => "New password does not match!"
                ]);
            }

            if(strlen($new_pw) < 6){
                error([
                    "message" => "Password length > 5 characters!"
                ]);
            }

            $hpw = user::create_password($new_pw);

            $this->update_password($hpw);
        }

        private function update_password($hpw){
            $rs = $this->pg_query(
                "UPDATE 
                    \"user\"
                SET 
                    password = '" . $hpw["hash_pw"] . "',
                    codesecurity = '" . $hpw["codesecurity"] . "' 
                WHERE 
                    user_id = " . $_SESSION["user"]["id"]
            );


            if($rs){
                echo json_encode([
                    "error" => 0,
                    "message" => "Your password has been changed successfully!"
                ]);
            }
        }
    }

?>