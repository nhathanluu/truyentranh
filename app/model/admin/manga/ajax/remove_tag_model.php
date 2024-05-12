<?php
	class remove_tag_model extends model{

		public function index($manga_id){

			$tag_name = @$_POST["tag_name"];
			
			$this->pg_connect();
			
			$this->pg_query("BEGIN");

			$tag_id = @$this->pg_select(
				"tag_id",
				"tags 
					WHERE 
						tag_name = '" . $tag_name . "'"
			)[0][0];

			if(!$tag_id){
				error([
					"message" => "Failed to delete!"
				]);
			}

			$rs = $this->pg_query(
				"DELETE FROM 
					mangas_tags 
				WHERE 
					tag_id = {$tag_id} AND manga_id = {$manga_id}");

			if($rs){

				$this->pg_query("COMMIT");

				echo json_encode([
					"error"   => 0,
					"message" => "deleted successfully"
				]);
			}
		}
	}
?>