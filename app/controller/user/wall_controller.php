<?php
    
    class wall_controller extends controller{

        function __construct(){

            if(!$_SESSION["user"]){
                header("Location:/login");
                die();
            }
        }

        public function index($p){

            $action = str_replace("-","_",$p[0]);
            $data   = [];
            

            $this->pg_connect();
            
            if($this->model("user/user_{$action}")){

                $data = $this->model->index();
            }
            
            $data["action"] = $action;

            $this->view("user/wall/{$action}",$data);

            if(myredis::global()->exists("ban/" . $_SESSION["user"]["id"])){
                $this->view->is_banned = 1;
            }

            $this->view->more_script = '<script src="' . TH_PUBLIC . '/js/user.js"></script>';
            $this->view->more_css    = '<link rel="stylesheet" type="text/css" href="' . TH_PUBLIC .'css/user.css">';
            $this->view->render();
        }
    }
?>