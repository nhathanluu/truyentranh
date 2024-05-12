<?php
	class ajax_comment_controller extends controller{

		function __construct(){

			if (!@$_SESSION["user"]["id"]){

				error([
					"message" => "You must login"
				]);
			}

			$this->pg_connect();
		}

		public function index($p){

			$model = $p[0];

			if($model == "add" || $model == "reply"){
				
				if(myredis::global()->exists("ban/" . $_SESSION["user"]["id"])){

					error([
						"message" => "Tài khoản đã bị khóa"
					]);
				}
			}

			$this->model("comment/ajax/" . $model . "_comment");
			$this->model->index();
		}
	}
?>