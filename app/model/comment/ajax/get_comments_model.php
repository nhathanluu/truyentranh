<?php
	class get_comments_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$model = pg_manga::get_comments($this,$manga_id);

			foreach ($model["comments"] as &$cmt){

				if($cmt["flag"] == 't'){
					$cmt["cmt_content"] = "<span class=\"h-deleted\">This comment has been deleted!</span>";
				}

				$cmt["cmt_date_published"] = time_elapsed_string($cmt["cmt_date_published"]);

				if($cmt["child_cmts"]){

					$tmp = json_decode($cmt["child_cmts"],1);

					foreach ($tmp as &$c_cmt){

						$c_cmt["cmt_date_published"] = time_elapsed_string($c_cmt["cmt_date_published"]);
					}

					$cmt["child_cmts"] = json_encode($tmp);
				}
			}

			echo json_encode($model);
		}
	}
?>