<?php
    class crawl_controller extends controller{
    	
    	function __construct(){

            user::is_admin();
        }

        public function page($p){
            
            $this->view("admin/crawl")->render();
        }
    }
?>