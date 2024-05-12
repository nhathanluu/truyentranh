<?php
	class list_model extends model{

		public function index(){

			$this->pg_connect();

			$count = @$this->pg_select(
				"count(*)",
				"report"
			)[0][0];

			$limit = 20;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$reports = $this->pg_select_obj(
				"m.manga_id,manga_name,manga_views,manga_comments,manga_cover_img,r_content,ct.chapter_number,ct.chapter_id,r_type,r_id,r_creation_date",
				"report rp
					INNER JOIN
						chapters ct on(rp.chapter_id = ct.chapter_id)
					INNER JOIN
						manga m on (m.manga_id = ct.manga_id)
					ORDER BY
						r_creation_date desc"
			);

			return [
				"reports" => $reports,
				"pagi"	 => $pagi,
				"page"	 => $page
			];
		}
	}
?>