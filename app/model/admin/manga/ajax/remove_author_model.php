<?php
	class remove_author_model extends model{

		public function index($manga_id){

			$author_name = @$_POST["author_name"];
			
			$this->pg_connect();
			
			$this->pg_query("BEGIN");

			$author_id = @$this->pg_select(
				"author_id",
				"authors 
					WHERE 
						author_name = '" . $author_name . "'"
			)[0][0];

			if(!$author_id){
				error([
					"message" => "Failed to delete!"
				]);
			}

			$rs = $this->pg_query(
				"DELETE FROM 
					mangas_authors 
				WHERE 
					author_id = {$author_id} AND manga_id = {$manga_id}");

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