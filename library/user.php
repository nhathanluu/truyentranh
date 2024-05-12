<?php
	
	const static_salt = "d3ViLcH4N";

	class user{

		static function create_password($password){
			
			$codesecurity = create_salt();
			$hash_pw = md5(static_salt . $password . $codesecurity);

			return [
				"hash_pw" 	   => $hash_pw,
				"codesecurity" => $codesecurity
			];
		}

		static function is_banned($header = 0){

			if(file_exists("ban/" . $_SESSION["user"]["id"])){

				if(!$header){

					error([
						"message" => "Your account has been banned"
					]);
				}

				return 1;
			}
		}

		static function is_admin($header = 0){
			// $codesecurity = create_salt();
			// echo $hash_pw = md5(static_salt . 'Admin@123' . $codesecurity);die;

			if($_SESSION["user"]["level"] != 1){

				header("Location:/");
				die();
			}
		}
	}
?>