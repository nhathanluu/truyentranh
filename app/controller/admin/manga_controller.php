<?php
    class manga_controller extends controller{

        function __construct(){

            user::is_admin();
        }
        
        public function page($p){

            $data = [];

            if($this->model("admin/manga/manga_{$p[0]}")){

                $data = $this->model->index();
            }

            $this->view("admin/manga/manga_{$p[0]}",$data)->render();
        }

        public function detail($p){

            $this->model("admin/manga/manga_detail");
            $data = $this->model->index($p[0]);
            
            if(!$data){
                header("Location:/admin/manga/list");
            }

            $this->view("admin/manga/manga_detail",$data)->render();
        }

        public function chapters($p){

            $this->model("admin/manga/manga_chapters");
            $data = $this->model->index($p[0]);
            
            if(!$data){
                header("Location:/admin/manga/list");
            }

            $this->view("admin/manga/manga_chapters",$data)->render();
        }
    }
?>