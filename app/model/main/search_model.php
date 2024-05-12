<?php
	class search_model extends model{

		public function index(){

			$query = @remove_ws(rm_sp(vn_to_str($_GET["query"])));

			$this->pg_connect();

			$rs = $this->pg_select_obj(
				"manga_id,manga_name,manga_views,manga_comments,manga_cover_img,(
					select 
						chapter_number
					from (
						SELECT manga_id,chapter_id,chapter_number,chapter_date_published
							from chapters b
						where b.manga_id = a.manga_id
							order by chapter_number desc
						LIMIT 1
					) as c2
				) as chapter",
				"manga a 
				WHERE 
					manga_search ILIKE '%" . $query . "%' 
				ORDER BY 
					manga_id DESC 
				LIMIT 
					10"
			);
			

			$data = [];

			foreach ($rs as &$manga){

				$manga_id   = $manga["manga_id"];
				$manga_name = $manga["manga_name"];
				$url = "/manga/" . url_name_replace($manga_name)  . "-" . $manga_id;

				$cover_img = SITE_URL . "img/" . $manga_id . "/" . $manga["manga_cover_img"];

				$manga["url"] = $url;
				$manga["cover_img"] =  $cover_img;

				unset($manga["manga_cover_img"]);
			}

			echo json_encode(["manga" => $rs]);
		}
	}
?>