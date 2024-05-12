<?php

	class genre_controller extends controller{

		function __construct(){

			$cur_url = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] 
     . explode('?', $_SERVER['REQUEST_URI'], 2)[0];

			if(isset($_GET["page"]) && $_GET["page"] <= 1){

				header("Location:" . $cur_url);
			}
		}

		public function index(){

			$this->model("main/genre");

			$data = $this->model->index("");
			$data["action"] = "";

			$this->view("main/genre",$data)->render();
		}

		public function genre($p){

			$genre = strtolower(urldecode($p[0]));
			$count = count($p); 

			$con  = [];
			$data = [];

			$status_url = "";
			$con["genre"] = $genre;

			if( !empty(@$p[1]) && !preg_match('/\?/',@$p[1]) ){
				if($p[1] == "da-hoan-thanh"){

					$e_status = "completed";

				}else{
					$e_status = "ongoing";
				}

				$data["status"] = $e_status;
				$con["status"]  = $e_status;
				$status_url     = "/" . $p[1];
			}
			

			$this->model("main/genre");
			try{

			    $manga_detail = $this->model->index($con);
			}catch (Exception $e) {

			    if($e->getMessage() == "tag_id"){
			    	
					_404_page();
				}
			}

			$data += $manga_detail;
			$data["action"]		 = "genre";
			
// 			echo '<pre>';
// 			var_dump($data);die;

			$this->view("main/genre",$data)->render();
		}

		public function status($p){

			$status = $p[0];

			if($status == "da-hoan-thanh"){
					$e_status = "completed";

				}else{
					$e_status = "ongoing";
				}

			$this->model("main/genre");
			$data = $this->model->index([
				"status" => $e_status
			]);

			$data["action"] = "status";
			$data["status"] = $e_status;

			$this->view("main/genre",$data)->render();
		}
	}
?>