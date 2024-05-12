<?php

	class _404_controller extends controller{

		public function index(){
			http_response_code(404);
			include VIEW . "404/index.phtml";
		}
	}
?>