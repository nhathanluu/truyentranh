<?php
	
	class ajax_user_controller extends controller{

		function __construct(){

			if(!$_SESSION["user"]){
				header("Location:/login");
				die();
			}

			$this->pg_connect();
		}

		public function index($p){

			$action = str_replace("-","_",$p[0]);

			$this->model("user/ajax/{$action}");
			$this->model->index();
		}
	}

?>