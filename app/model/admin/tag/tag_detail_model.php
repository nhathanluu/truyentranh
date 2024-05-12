<?php
	class tag_detail_model extends model{

		public function index($tag_id){

			$this->pg_connect();

			$rs = $this->pg_select_obj(
				"*",
				"tags
					WHERE 
						tag_id = {$tag_id}"
			)[0];

			if(!$rs){
				header("Location:/admin/tag/list");
				die();
			}

			return $rs;
		}
	}
?>