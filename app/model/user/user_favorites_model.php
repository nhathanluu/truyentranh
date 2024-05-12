<?php
	class user_favorites_model extends model{

		public function index(){

			$count = @$this->pg_select(
				"count(*)",
				"favorites 
					WHERE
						user_id = " . $_SESSION["user"]["id"]
			)[0][0];

			if(!$count){
				return;
			}

			$user_id  = $_SESSION["user"]["id"];

			$limit = 15;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$rs = $this->pg_select_obj(
				"m.manga_id,manga_name,manga_cover_img",
				"manga m
					INNER JOIN favorites f on (m.manga_id = f.manga_id)
				WHERE f.user_id = " . $user_id . " ORDER BY f_date desc LIMIT {$limit} OFFSET {$offset}"
			);
			
			return [
				"favorites" => $rs,
				"pagi"		=> $pagi,
				"page"		=> $page
			];
		}
	}
?>