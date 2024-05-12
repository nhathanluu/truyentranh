<?php
	class user_register_model extends model{

		private $message = [];

		public function register(){

			$captcha		 = $_POST["captcha"];
			$username 		 = $_POST["username"];
			$password 		 = $_POST["password"];
			$repeat_password = $_POST["repeat-password"];


			if($captcha != $_SESSION["captcha"]){
				
				$message["captcha"] = "Sai captcha!";

				return $message;
			}

			if(!$this->_username_check($username)){

				$message["user"] = "Tên tài khoản không phù hợp!";

				return $message;
			}


			if(!$this->_password_check($_POST["password"])){

				$message["password"] = "Mật khẩu không phù hợp";

				return $message;
			}


			if($repeat_password != $password){
				
				$message["repeat-password"] = "Mật khẩu không khớp!";

				return $message;
			}

			$this->pg_connect();

			$data = $this->pg_select(
				"1",
				"\"user\"",
				"account = '" . $username . "'"
			);

			if (!$data){
				
				$this->_add_new_user($username,$password);

				$message["ok"] = "Thành công!!";

				return $message;

			}else{

				$message["user"] = "Tài khoản đã tồn tại!";

				return $message;
			}
		}

		private function _username_check($username){

			if(preg_match('/[^a-zA-Z0-9]/',$username) 
				|| preg_match('/admin/',$username) 
					|| (strlen($username) < 8 && strlen($username) < 25)){

				return false;
			}

			return true;
		}

		private function _password_check($password){

			// if(preg_match('/[^a-zA-Z0-9]/',$password) || (strlen($password) < 6 && strlen($password) < 30)){

			// 	return false;
			// }

			return true;
		}

		private function _add_new_user($username,$password){

			$static_salt  = "d3ViLcH4N";
			$codesecurity = create_salt();

			$password = md5($static_salt . $password . $codesecurity);
			
			$this->pg_query("INSERT INTO 
				\"user\" (account,password,codesecurity,level,nickname,create_date) 
				VALUES 
					('" . $username . "','" . $password . "','" . $codesecurity . "',0,'" . $username . "','" . date("Y-m-d H:i:s") . "')");
		}
	}
?>
