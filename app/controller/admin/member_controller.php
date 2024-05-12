<?php
    class member_controller extends controller{
    	
    	function __construct(){

            user::is_admin();
        }

        public function page($p){

            $data = [];

            $model = str_replace("-","_",$p[0]);
            $id    = @$p[1];

            if($this->model("admin/member/member_{$model}")){

                $data = $this->model->index($id);
            }

            $this->view("admin/member/member_{$model}",$data)->render();
        }
    }
?>