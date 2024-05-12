<?php
	class add_comment_model extends model{

		public function index(){

			$manga_id 		 = @$_POST["manga_id"];
			$comment_content = cmt::xss(@$_POST["comment_content"]);
			$chapter_id 	 = @$_POST["chapter_id"] == 0 ? 'NULL' : @$_POST["chapter_id"];
			$user_id    	 = $_SESSION["user"]["id"];

			
			if(isset($_SESSION["cmt_sleep"])){
				
				$diff = strtotime(date('Y-m-d H:i:s')) - $_SESSION["cmt_sleep"];

				if ($diff <= 15){

					$seconds = 15 - $diff;

					error([
						"message" => "Hãy chờ lượt comment kế tiếp sau {$seconds} giây"
					]);
				}
			}

			if(!$manga_id){
				error();
			}

			$cmt_len = strlen($comment_content);

			if($cmt_len <= 10){
				error([
					"message" => "Tối thiểu 10 ký tự"
				]);
			}

			if($cmt_len > 1000){
				error([
					"message" => "Tối đa 500 ký tự"
				]);
			}

			$cmt_date_published = date("Y-m-d H:i:s");
			$insert = @$this->pg_query(
				"INSERT INTO 
					comments(cmt_content,user_id,manga_id,chapter_id,cmt_date_published) 
				VALUES
					('{$comment_content}',{$user_id},{$manga_id},{$chapter_id},'{$cmt_date_published}') RETURNING cmt_id"
			);

			if(!$insert){
				error();
			}

			$cmt_id = pg_fetch_row($insert)[0];

			$cmt = $this->pg_select_obj(
				"cmt_id,cmt_content,chapter_number,nickname,cmt_date_published,avatar",
				"comments b
					LEFT JOIN 
						chapters ct on(ct.chapter_id = b.chapter_id)
					INNER JOIN 
						\"user\" a on (a.user_id = b.user_id)
				WHERE 
					cmt_id = " . $cmt_id
			)[0];

			$_SESSION["cmt_sleep"] =  strtotime(date('Y-m-d H:i:s'));

			//cmt_date_published
			$cmt['cmt_date_published'] = time_elapsed_string($cmt['cmt_date_published']);

			echo json_encode([
				"cmt" => $cmt
			]);
		}
	}
?>