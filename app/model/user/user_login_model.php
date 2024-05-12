<?php

	class user_login_model extends model {

		public function login(){

			$static_salt = "d3ViLcH4N";
			$message = [];

			$username = remove_hack($_POST["username"]);
			$password = remove_hack($_POST["password"]);

			$this->pg_connect();

			$data = @$this->pg_select_obj(
				"user_id,
				account,
				password,
				codesecurity,
				level,
				nickname,
				avatar",
				"\"user\"
					WHERE
						account = '" . $username . "'"
			)[0];

			if(!$data){

				$message["user"] = "Tài khoản không tồn tại!";

				return $message;
			}

			$user_password = $data["password"];
			$codesecurity  = $data["codesecurity"];

			$hash_pass = md5($static_salt . $password . trim($codesecurity));

			if($hash_pass != trim($user_password)) {

				$message["password"] = "Sai mật khẩu!";

				return $message;
			}

			if($data["level"] == 3){

				myredis::global()->set("ban/" . $data["user_id"],"");
                myredis::global()->expire("ban/" . $data["user_id"],86400);
			}

			$_SESSION["user"] = [
				"account"  => $data["account"],
				"id"       => $data["user_id"],
				"level"    => $data["level"],
				"nickname" => $data["nickname"],
				"avatar"   => $data["avatar"]
			];
			
			return $message;
		}
	}
?>