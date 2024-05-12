<?php
    class ajax_controller extends controller{

        function __construct(){

            user::is_admin();
        }

        public function manga($p){

            $model    = str_replace("-","_",$p[0]);
            $manga_id = @$p[1]; 

            $this->model("admin/manga/ajax/{$model}");
            $this->model->index($manga_id);
        }

        public function new_manga($p){

            $model = str_replace("-","_",$p[0]);

            $this->model("admin/manga/ajax/{$model}");
            $this->model->index();
        }

        public function chapter($p){

            $model = str_replace("-","_",$p[0]);

            $this->model("admin/chapter/ajax/chapter_{$model}");
            $this->model->index($p[1]);
        }

        public function member($p){
            
            $this->pg_connect();

            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            $this->model("admin/member/ajax/member_{$model}");
            $this->model->index($id);
        }

        public function tag($p){

            $this->pg_connect();
            
            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            $this->model("admin/tag/ajax/{$model}");
            $this->model->index($id);
        }

        public function report($p){

            $this->pg_connect();
            
            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            $this->model("admin/report/ajax/report_{$model}");
            $this->model->index($id);
        }

        public function crawl($p){

            $this->pg_connect();
            
            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            $this->model("admin/crawl/ajax/{$model}");
            $this->model->index($id);
        }

        public function image($p){

            $this->pg_connect();
            
            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            $this->model("admin/image/ajax/{$model}");
            $this->model->index($id);
        }

        public function others($p){

            $model = str_replace("-","_",$p[0]);

            $this->model("admin/others/ajax/{$model}");
            $this->model->index();
        }
    }
?>