<?php
	class search_page_model extends model{

		public function index($query){

			if(strlen($query) < 1){
				return "";
			}

			$str = $query;
			$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
            $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
            $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
            $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
            $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
            $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
            $str = preg_replace("/(đ)/", "d", $str);
            $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
            $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
            $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
            $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
            $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
            $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
            $str = preg_replace("/(Đ)/", "D", $str);
            //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
            $str = strtolower($str);

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
				WHERE 
					search_latin ILIKE '%" . strtolower($str) . "%' or manga_name ILIKE '%" . $query . "%' 
				ORDER BY
					manga_updated desc
				LIMIT 
					{$limit} 
				OFFSET 
					{$offset}"
			);

			// var_dump($rs);die;

			return [
				"manga_list" => $rs,
				"pagi"		 => $pagi,
				"page"		 => $page
			];
		}
	}
?>