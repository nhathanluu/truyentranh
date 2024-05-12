<?php
	class dashboard_controller extends controller{

        function __construct(){
            
           user::is_admin();
        }

		public function index(){

            $this->model("admin/dashboard");
            $data = $this->model->index();

            $this->view("admin/dashboard",$data)->render();
        }
	}
?>