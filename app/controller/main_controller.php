<?php
    class main_controller extends controller{

        public function index($p){
            
            if(isset($_GET["page"]) && $_GET["page"] <= 1){

                header("Location:" . cur_url_without_paras());
            }
            
           
            
            $this->model("main/list");
            $data = $this->model->index();

            $data["pagi"]["url"] = "/moi-cap-nhat";

            $this->view("main/home",$data)->render();
        }

        public function history(){
            $this->view("main/history")->render();
        }

        public function latest_manga(){
            $this->model("main/latest_manga");
            $data = $this->model->index();

            $data["pagi"]["url"] = "/moi-cap-nhat";

            $this->view("main/manga_page",$data)->render();
        }
        
        public function latest_manga_paginate($p){
            if (!empty($p[0])) {
                $_GET["page"] = $p[0];
            }
            $this->model("main/latest_manga");
            $data = $this->model->index();

            $data["pagi"]["url"] = "/moi-cap-nhat";

            $this->view("main/manga_page",$data)->render();
        }

        public function manga($p){

            $manga_id       = $p[1];
            $url_manga_name = $p[0];
            $this->model("main/manga");
            $data = $this->model->index($manga_id,$url_manga_name);
            // var_dump($data);die;
            if(!$data){
                _404_page();
            }

            $this->view("main/manga",$data)->render();
        }

        public function chapter($p){

            $chapter_number  = (float)$p[2];
            $manga_url       = $p[0];
            $manga_id        = $p[1];

            $this->model("main/chapter");

            $data = $this->model->index($manga_id,$chapter_number);

            if(!$data){
                $this->view("404/index")->render();
                die();
            }

            $url_check = url_name_replace($data["chapter_detail"]["manga_name"]);

            if($url_check != $manga_url){

                $chapter_number = $data["chapter_detail"]["chapter_number"];
                $chapter_id     = $data["chapter_detail"]["chapter_id"];

                header("Location:/manga/{$url_check}-{$manga_id}/chapter-" . $chapter_number);  
            }

            $this->view("main/chapter",$data)->render();
        }

        public function chapter_header($p){

            $chapter_id  = $p[2];
            $chapter_number_url = $p[1];

            $this->model("main/chapter_header");
            $this->model->index($chapter_id);
        }

        public function gender($p){
            $this->model("main/gender");
            $data = $this->model->index($p[0]);
            $data["gender"] = $p[0];
            $this->view("main/gender",$data)->render();
        }
    }
?>