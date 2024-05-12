<?php
	class detail_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$manga = $this->pg_select_obj(
				"manga_id,manga_name,manga_cover_img",
				"manga
					WHERE
						manga_id = {$manga_id}"
			)[0];

			if(!$manga){
				header("Location:/admin/report/list");
			}

			

			$reports = $this->pg_select_obj(
				"*",
				"report rp
					INNER JOIN
						chapters ct on(ct.chapter_id = rp.chapter_id)
					WHERE
						manga_id = {$manga_id}"
			);

			return [
				"manga" => $manga
			];
		}
	}
?>