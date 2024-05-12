<?php

	class user_comments_model extends model {

		public function index(){

			$count = @$this->pg_select(
				"count(*)",
				"comments 
					WHERE
						user_id = " . $_SESSION["user"]["id"]
			)[0][0];

			if(!$count){
				return;
			}

			$limit = 15;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$rs = $this->pg_select_obj(
				"m.manga_id,manga_name,manga_views,manga_comments,manga_cover_img,cmt_date_published,cmt_content",
				"comments b
					INNER JOIN 
						manga m on(b.manga_id = m.manga_id)
				WHERE
					user_id = " . $_SESSION["user"]["id"] . " AND flag != 't'
					ORDER BY
						cmt_date_published desc
					LIMIT 
						{$limit}
					OFFSET
						{$offset}"
			);

			return [
				"cmts" => $rs,
				"pagi" => $pagi,
				"page" => $page
			];
		}
	}
?>