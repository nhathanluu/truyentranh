<?php
	class manga_detail_model extends model{

		public function index($manga_id){

			$this->pg_connect();
			
			$rs = $this->pg_select_obj(
				"manga_id,manga_name,manga_others_name,manga_description,manga_status,manga_cover_img",
				"manga a
					WHERE 
						manga_id = {$manga_id}
					ORDER BY 
						manga_updated DESC"
			)[0];

			if(!$rs){
				return "";
			}

			$tags = @$this->pg_select_obj(
				"tag_name,a.tag_id",
				"tags a 
					INNER JOIN 
						mangas_tags b on(a.tag_id = b.tag_id)
				WHERE 
					manga_id = " . $manga_id
			);

			$authors = @$this->pg_select_obj(
				"author_name",
				"authors a 
					INNER JOIN 
						mangas_authors b on(a.author_id = b.author_id)
				WHERE 
					manga_id = " . $manga_id
			);

			return [
				"detail"  => $rs,
				"tags"    => $tags,
				"authors" => $authors
			];
		}
	}
?>