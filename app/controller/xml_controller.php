<?php
	class xml_controller extends controller{

		public function index($p){

			$model = str_replace("-","_",$p[0]);

			if($this->model("xml/{$model}")){

				header('Content-type: application/xml');
            	echo '<?xml version="1.0" encoding="UTF-8"?>';

				$this->model->index();

			}else{

				$this->view("404/index")->render();
			}
		}
	}
?>