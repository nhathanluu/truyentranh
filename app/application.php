<?php

	class application{

		private $route;
		private $controller = "_404_controller";
		private $dir = "";

		protected $action = "index";
		protected $params;

		public function __construct($route){

			$this->route = $route;

			$this->_prepare_URL();

			$path = CONTROLLER . $this->dir . $this->controller . '.php';

			if(file_exists($path)){

				require_once($path);

				if(method_exists($this->controller,$this->action)){

					$this->controller = new $this->controller();
					$this->controller->{$this->action}($this->params);

					return;
				}

				derror::page_404();
			}
		}

		private function _prepare_URL(){

			$request = ltrim($_SERVER['REQUEST_URI'],"/");
			$request = preg_replace('#/{2,}#', '/',$request);
						
			$this->_validate_URL($request);
		}

		private function _validate_URL($url){
			
			foreach ($this->route as $key => $value) {

				if (preg_match("#^".$key."\/*(|\?.*?)$#i",$url, $matches)) {

					unset($matches[0]);

					$this->_set_route($value,
						array_values(
							array_filter($matches,function($value){
								return ($value || is_numeric($value));
							})
						)
					);
					break;
				}
			}
		}

		private function _set_route($ctrl,$params = []){
			
			$ctrl = explode("/",$ctrl);
			
			$length = count($ctrl);

			$controller = $ctrl[$length - 2];
			$action     = $ctrl[$length - 1];

			if(preg_match('/\$([0-9]+)/',$controller,$m)){
				$controller = $params[$m[1] - 1];
			}

			if(preg_match('/\$([0-9]+)/',$action,$m)){
				$action = $params[$m[1] - 1];
			}
			
			$this->controller = $controller . "_controller";
			$this->action = $action;

			$dir = "";
			
			for ($i = 0; $i < $length - 2; $i++){ 

				$dir = $ctrl[$i] . "/";
			}

			$this->dir 	  = $dir;
			$this->params = $params;
		}
	}
?>