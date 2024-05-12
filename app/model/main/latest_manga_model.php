<?php
	class latest_manga_model extends model{

		public function index(){

			$this->pg_connect();

			$count = @$this->pg_select(
				"count(*)",
				"manga"
			)[0][0];

			$limit = 40;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$rs = $this->pg_select_obj(
				"manga_name,manga_views,manga_comments,manga_cover_img,a.manga_id,(
					select 
						json_agg(c2.*) 
					from (
						SELECT manga_id,chapter_id,chapter_number,chapter_date_published
							from chapters b
						where b.manga_id = a.manga_id
							order by chapter_number desc
						LIMIT 2 
					) as c2
					group by c2.manga_id
				) as manga_chapters",
				"manga a 
					ORDER BY
						manga_updated desc
					LIMIT 
						{$limit} 
					OFFSET 
						{$offset}"
			);

			return [
				"manga_list" => $rs,
				"pagi"		 => $pagi,
				"page"		 => $page
			];
		}
	}
?>
				




