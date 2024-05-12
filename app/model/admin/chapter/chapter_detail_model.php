<?php
	class chapter_detail_model extends model{

		public function index($chapter_id){

			$this->pg_connect();

			$rs = @$this->pg_select_obj(
				"a.manga_id,manga_name,chapter_id,chapter_number,chapter_title,chapter_content",
				"chapters a
					INNER JOIN 
						manga b on (a.manga_id = b.manga_id)
					WHERE 
						chapter_id = " . $chapter_id
			)[0];

			if(!$rs){
				return "";
			}

			return [
				"edit" => $rs
			];
		}
	}
?>