<?php
	class author_controller extends controller{

		public function index($p){

			$author = @strtolower(urldecode($p[0]));
			
			$this->model("main/author");
			$data = $this->model->index($author);

			$this->view("main/author",$data)->render();
		}
	}
?>