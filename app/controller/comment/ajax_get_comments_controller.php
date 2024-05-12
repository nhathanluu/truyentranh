<?php
	class ajax_get_comments_controller extends controller{

		public function index($p){

			$this->model("comment/ajax/{$p['0']}_comments");
			$this->model->index($p[1]);
		}
	}
?>