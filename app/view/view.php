<?php
    class View{

        protected $view_file;
        protected $view_data;


        public function __construct($view_file,$view_data){

            $this->view_file = $view_file;
            $this->view_data = $view_data;
        }

        public function render(){

            $top         = "";
            $script      = "";
            $meta        = "";
            $script_text = "";
            $description = "";

            include VIEW . $this->view_file.'.phtml';
        }


        public function getData(){

            return $this->view_data;
        }
    }
?>