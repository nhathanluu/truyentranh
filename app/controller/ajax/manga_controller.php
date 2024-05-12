<?php
	class manga_controller extends controller{

		function __construct(){

			if(!@$_SESSION["user"]){

				error([
					"message" => "Hãy đăng nhập để thực hiện chức năng này"
				]);
			}
		}

		public function add_to_favorites(){
			$this->model("ajax/add_to_favorites");
			$this->model->index();
		}
	}
?>