<?php
	class reply_comment_model extends model{

		public function index(){

			$manga_id 		 = @$_POST["manga_id"];
			$comment_content = cmt::xss(@$_POST["comment_content"]);
			$chapter_id 	 = @$_POST["chapter_id"] == 0 ? 'NULL' : @$_POST["chapter_id"];
			$reply_id 		 = @$_POST["reply_id"];
			$user_id    	 = $_SESSION["user"]["id"];

			if(isset($_SESSION["cmt_sleep"])){
				
				$diff = strtotime(date('Y-m-d H:i:s')) - $_SESSION["cmt_sleep"];

				if ($diff <= 15){

					$seconds = 15 - $diff;

					error([
						"message" => "You can comment after {$seconds} seconds"
					]);
				}
			}

			if(!$reply_id || !$manga_id){
				error();
			}

			$cmt_len = strlen($comment_content);

			if($cmt_len <= 10){
				error([
					"message" => "Minimum 10 characters"
				]);
			}

			if($cmt_len > 1000){
				error([
					"message" => "Maximum 500 characters"
				]);
			}
			
			$insert = $this->pg_query(
				"INSERT INTO 
					comments(cmt_content,user_id,manga_id,chapter_id,parent_cmt_id,cmt_date_published) 
				VALUES
					('{$comment_content}',{$user_id},{$manga_id},{$chapter_id},{$reply_id},NOW()) RETURNING cmt_id"
			);

			if(!$insert){
				error([
					"message" => "insert"
				]);
			}

			$cmt_id = pg_fetch_row($insert)[0];

			$cmt = $this->pg_select_obj(
				"cmt_content,nickname,chapter_number,cmt_date_published,avatar",
				"comments b
					LEFT JOIN 
						chapters ct on(ct.chapter_id = b.chapter_id)
					INNER JOIN 
						\"user\" a on (a.user_id = b.user_id)
				WHERE 
					cmt_id = " . $cmt_id
			)[0];

			$_SESSION["cmt_sleep"] =  strtotime(date('Y-m-d H:i:s'));

			echo json_encode([
				"cmt" => $cmt
			]);
		}
	}
?>