<?php
	
	class controller extends model{

		protected $view;
		protected $model;

		public function view($viewName,$data = []){

			require_once(VIEW .'view.php');
			$this->view = new View($viewName,$data);
			return $this->view;
		}

		public function model($modelName){

			$modelName = $modelName."_model";

			if (file_exists(MODEL . $modelName . ".php")) {
				
				$r_model_name = explode('/',$modelName);
				$r_model_name = $r_model_name[count($r_model_name) - 1];

				require_once MODEL.$modelName.".php";
				$this->model = new $r_model_name();

				if(@$this->pg_conn){
					$this->model->set_conn($this->pg_conn);
				}
				
				return 1;

			}else{

				return 0;
			}
		}
	}
?>