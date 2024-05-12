<?php
	class chapter_header_model extends model{

		public function index($chapter_id){

			$this->pg_connect();

			$rs = @$this->pg_select_obj(
				"a.manga_id,manga_others_name,manga_name,chapter_id,chapter_title,chapter_number,chapter_content,server_name,chapter_id",
				"chapters b 
					INNER JOIN 
						manga a on(a.manga_id = b.manga_id)
					INNER JOIN
						server_img si on(si.server_id = b.server_id)
					WHERE 
						chapter_id = {$chapter_id}"
			)[0];

			if($rs){
				header("Location:/manga/" . url_name_replace($rs["manga_name"]) . "-{$rs['manga_id']}/chapter-{$rs['chapter_number']}");
			}else{

				include(VIEW . "404/index.phtml");
			}
		}
	}
?>