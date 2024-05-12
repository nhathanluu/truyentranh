<?php
	class chapter_add_model extends model{

		public function index($manga_id){

			$chapter_title   = @pg_replace($_POST["chapter_title"]);
            $chapter_number  = $this->chapter_number(@$_POST["chapter_number"]);
            $chapter_content = @pg_replace($_POST["chapter_content"]);

            $this->pg_connect();

            pg_query("BEGIN");

            $insert = @pg_query("
            	INSERT INTO 
            		chapters (
            		chapter_title,
            		chapter_number,
            		chapter_content,
            		chapter_date_published,
            		manga_id
				) VALUES (
					'{$chapter_title}',
					$chapter_number,
					'{$chapter_content}',
					'" . date("Y-m-d H:i:s") . "',
					$manga_id
				) RETURNING chapter_id"
			);

			if($insert){

				pg_query("COMMIT");
				
				echo json_encode([
					"error"      => 0,
					"message"    => "Thêm chương mới thành công!",
					"chapter_id" => pg_fetch_row($insert)[0]
				]); 

			}else{
				
				error();
			}
		}

		private function chapter_number($num){

            if(!preg_match('/[0-9\.]+/',$num)){
                error([
                    "message" => "Thứ tự chưong không được trống"
                ]);
            }

            return $num;
        }
	}
?>