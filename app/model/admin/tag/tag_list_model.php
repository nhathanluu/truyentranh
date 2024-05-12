<?php
	class tag_list_model extends model{

		public function index(){

			$this->pg_connect();

			$sort = @$_GET["sort"];

			if($sort == "id"){
				$order_by = "tag_id asc";
			}elseif($sort == "manga"){
				$order_by = "count desc";
			}else{
				$order_by = "tag_name asc";
			}

			return $this->pg_select_obj(
				"tag_id,tag_name,(
					SELECT 
						count(*)
					FROM
						mangas_tags mt
					WHERE
						mt.tag_id = a.tag_id
				) as count",
				"tags a
					ORDER BY 
						{$order_by}" 
			);
		}
	}
?>