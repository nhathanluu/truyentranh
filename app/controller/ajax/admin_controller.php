<?php
	class admin_controller extends controller{

		public function index($p){

			$model = str_replace("-","_",$p[0]);
			
			$this->model("ajax/" . $model);
			$this->model->index();
		}
	}
?>