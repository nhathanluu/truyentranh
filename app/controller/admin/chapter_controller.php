<?php
	class chapter_controller extends controller{

        function __construct(){
            
            user::is_admin();
        }

		public function page($p){
			
			$model    = str_replace("-","_",$p[0]);
			$manga_id = $p[1];

            $data = [];

            if($this->model("admin/chapter/chapter_{$p[0]}")){

                $data = $this->model->index($manga_id);

                if(!$data){
                	header("Location:/admin/manga/list");
                }
            }

            $this->view("admin/chapter/chapter_{$p[0]}",$data)->render();
        }
	}
?>