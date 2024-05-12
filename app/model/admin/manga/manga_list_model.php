<?php
	class manga_list_model extends model{

		public function index(){

			$this->pg_connect();

			$date = @$_GET["date"];

			$where = "";

			if($date == "today"){

				$where = "WHERE manga_date_published BETWEEN NOW() - INTERVAL '24 HOURS' AND NOW()";

			}elseif($date == "yesterday"){

				$where = "WHERE manga_date_published > TIMESTAMP 'yesterday'  ";
			}elseif($date == "week"){

				$where = "WHERE manga_date_published > current_date - interval '7' day";
			}

			$count = @$this->pg_select(
				"count(*)",
				"manga
					$where"
			)[0][0];

			$sort = @$_GET["sort"];

			$limit = 40;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$order = "";

			if($sort == "views"){

				$order = "manga_views desc";

			}elseif($sort == "chapters"){

				$order = "total_chapters desc";
				
			}else{

				$order = "manga_updated desc";
			}

			$rs = $this->pg_select_obj(
				"manga_id,manga_name,manga_views,manga_comments,manga_cover_img,total_chapters,manga_updated",
				"manga 
					{$where}
					ORDER BY 
						{$order}
					LIMIT 
						{$limit} 
					OFFSET 
						{$offset}"
			);

			return [
				"manga" => $rs,
				"pagi"  => $pagi,
				"page"  => $page
			];
		}
	}
?>