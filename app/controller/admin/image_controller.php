<?php
    class image_controller extends controller{
        
        function __construct(){

            user::is_admin();
        }

        public function page($p){

            $data = [];

            if($this->model("admin/image/img_list")){

                $data = $this->model->index();
            }

            $this->view("admin/image/image",$data)->render();
        }
    }
?>