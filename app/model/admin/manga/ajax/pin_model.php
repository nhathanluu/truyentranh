<?php
	class pin_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$check1 = @$this->pg_select(
				"1",
				"manga
					WHERE
						manga_id = {$manga_id}"
			);

			if(!$check1){
				error([
					"message" => "ID truyện không tồn tại"
				]);
			}

			$check2 = @$this->pg_select(
				"1",
				"pin
					WHERE
						p_manga_id = {$manga_id}"
			);

			if($check2){
				error([
					"message" => "Truyện đã được ghim!"
				]);
			}

			$rs = $this->pg_query(
				"INSERT INTO 
					pin (
						p_manga_id,
						p_date
					)
				VALUES (
					{$manga_id},
					'" . now() . "'
				)"
			);

			myredis::global()->del("pin2");

			$rs2 = $this->pg_select_obj(
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
					manga_id = {$manga_id}"
			);


			foreach ($rs2 as &$manga){

				$manga_id   = $manga["manga_id"];
				$manga_name = $manga["manga_name"];
				$url = "/manga/" . url_name_replace($manga_name)  . "-" . $manga_id;

				$cover_img = SITE_URL . "img/" . $manga_id . "/" . $manga["manga_cover_img"];

				$manga["url"] = $url;
				$manga["cover_img"] =  $cover_img;

				unset($manga["manga_cover_img"]);
			}

			success([
				"manga" => $rs2[0]
			]);
		}
	}
?>