<?php

	class account_controller extends controller{

		public function index($p){

			$action = $p[0];

			if($action == "dang-ky"){
				$action = "register";
			}elseif($action == "dang-nhap"){
				$action = "login";
			}else{
				$action = "logout";
			}

			$this->$action();
		}

		private function login(){

			if (isset($_SESSION["user"])) {

				header("Location:/");
				die();
			}

			$message = '';

			if (isset($_POST["ok"])){

				$this->model("user/user_login");

				$message = $this->model->login();

				if(!$message){

					if(@$_GET["return"]){

						header("Location:" . $_GET["return"]);
						
					}else{
						
						header("location:/");
					}

					die();
				}
			}	

			$this->view("user/account/login",[

				"message" => $message

			])->render();
		}

		private function register(){
			
			if (isset($_SESSION["user"])) {

				header("Location:/");
			}

			$message = '';

			if (isset($_POST["ok"])){
				
				$this->model("user/user_register");

				$message = $this->model->register();
			}

			$this->view("user/account/register",[

				"message" => $message

			])->render();
		}

		private function logout(){

			session_destroy();

			if(@$_GET["return"]){

				header("Location:" . $_GET["return"]);

			}else{

				header("Location:/");
			}

		}
	}
?>