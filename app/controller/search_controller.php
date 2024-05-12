<?php
	class search_controller extends controller{

		public function index(){

			session_write_close();
			
			$this->model("main/search");
			$this->model->index();
		}

		public function page(){

			$query = @remove_ws(rm_sp(($_GET["tu-khoa"])));

			$this->model("main/search_page");
			$data = $this->model->index($query);
			
			$this->view("main/search",$data)->render();
		}

		public function tag(){

			$query = @convert_to_search_str($_GET["query"]);

			if(strlen($query) < 2){
				return 0;
			}

			$this->pg_connect();

			$rs = $this->pg_select_obj(
				"tag_name",
				"tags
					WHERE 
						tag_search ILIKE '%" . $query . "%' 
					LIMIT 10"
			);

			echo json_encode($rs);
		}
	}
?>