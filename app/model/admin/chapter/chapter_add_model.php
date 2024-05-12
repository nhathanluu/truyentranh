<?php
	class chapter_add_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$rs = @$this->pg_select_obj(
				"manga_id,manga_name",
				"manga 
					WHERE 
						manga_id = " . $manga_id
			)[0];

			if(!$rs){
				return "";
			}

			return $rs;
		}
	}
?>