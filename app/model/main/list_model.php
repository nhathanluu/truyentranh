<?php
	class list_model extends model{

		public function index(){

			$this->pg_connect();
			
			$rs = $this->pg_select_obj(
				"manga_name,manga_views, manga_comments,manga_cover_img,a.manga_id,(
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
						20"
			);

			$new_cmts = $this->pg_select_obj(
				"cmt_id,cmt_content,nickname,cmt_date_published,avatar,chapter_number,b.manga_id,manga_name",
				"comments b
					INNER JOIN
						\"user\" a on (a.user_id = b.user_id)
					INNER JOIN
						manga m on (m.manga_id = b.manga_id)
					LEFT JOIN
						chapters ct on (ct.chapter_id = b.chapter_id)
				WHERE 
					flag != 't'
				ORDER BY 
					cmt_id 
						DESC 
							LIMIT 
								30"
			);

			myredis::global()->get("gag");

			$rank = null;
			$pin = null;
			
			return [
				"manga_list" => $rs,
				"new_cmts"   => $new_cmts,
				"rank"		 => @$rank,
				"pin"		 => $pin,
			];
		}
	}
?>
				




