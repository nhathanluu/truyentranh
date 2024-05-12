<?php
	class show_comments_model extends model{

		public function index($cmt_id){

			$this->pg_connect();

			$cmts = @$this->pg_select_obj(
				"cmt_id,cmt_content,nickname,cmt_date_published,avatar,chapter_number",
				"comments a 
					LEFT JOIN
						chapters b on (a.chapter_id = b.chapter_id)
					INNER JOIN 
                        \"user\" u on (u.user_id = a.user_id)
					WHERE 
						parent_cmt_id = " . $cmt_id . " AND cmt_id < " . $_POST["h_cmt_id"] . "
					ORDER BY cmt_id desc"
			);

			if($cmts){

				foreach ($cmts as &$cmt){
					
					$cmt["cmt_date_published"] = time_elapsed_string($cmt["cmt_date_published"]);		
				}

				echo json_encode(["cmts" => $cmts]);

			}else{

				error();
			}
		}
	}
?>