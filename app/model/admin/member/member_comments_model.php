<?php
	class member_comments_model extends model{

		public function index($user_id){

			$this->pg_connect();

			$user = $this->pg_select_obj(
				"user_id,account",
				"\"user\"
					WHERE 
						user_id = " . $user_id
			)[0];

			if(!$user){
				header("Location:/admin/member/list");
				die();
			}

			$count = @$this->pg_select(
				"count(*)",
				"comments 
					WHERE
						user_id = " . $user_id
			)[0][0];

			if(!$count){
				return [
					"user" => $user
				];
			}

			$limit = 15;
			$pagi  = get_pagi($count,$limit,$offset);

			$rs = $this->pg_select_obj(
				"m.manga_id,manga_name,manga_views,manga_comments,manga_cover_img,cmt_id,cmt_date_published,cmt_content",
				"comments b
					INNER JOIN 
						manga m on(b.manga_id = m.manga_id)
				WHERE
					user_id = " . $user_id . " AND flag != 't'
					ORDER BY
						cmt_date_published desc
					LIMIT 
						{$limit}
					OFFSET
						{$offset}"
			);

			return [
				"cmts"  => $rs,
				"pagi"  => $pagi,
				"user"  => $user,
				"page"  => $offset + 1
			];
		}
	}
?>