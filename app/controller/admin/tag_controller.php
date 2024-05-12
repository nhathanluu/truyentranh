<?php
    class tag_controller extends controller{
        
        function __construct(){

            user::is_admin();
        }

        public function page($p){

            $data = [];

            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            if($this->model("admin/tag/tag_{$model}")){

                $data = $this->model->index($id);
            }

            $this->view("admin/tag/tag_{$model}",$data)->render();
        }
    }
?>