<?php
    class others_controller extends controller{
    	
    	function __construct(){

            user::is_admin();
        }

        public function page($p){

            $this->model("admin/others/page");
            $data = $this->model->index();
            
            $this->view("admin/others",$data)->render();
        }
    }
?>