<?php
	class manga_chapters_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$manga = $this->pg_select_obj(
				"manga_id,manga_name",
				"manga WHERE 
					manga_id = " . $manga_id
			)[0];

			if(!$manga){
				return "";
			}

			$chapters = $this->pg_select_obj(
				"chapter_id,chapter_title,chapter_number",
				"chapters 
					WHERE 
						manga_id =" .  $manga_id . " 
					ORDER BY 
						chapter_number DESC"
			);

			return [
				"chapters" => $chapters,
				"manga"	   => $manga
			];
		}
	}
?>