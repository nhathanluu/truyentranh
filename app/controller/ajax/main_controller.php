<?php
	class main_controller extends controller{

		public function index($p){

			$model = str_replace("-","_",$p[0]);
			
			$id = @$p[1];

			$this->model("ajax/" . $model);
			$this->model->index($id);
		}
	}
?>