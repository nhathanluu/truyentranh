<?php
	class chapter_model extends model{

		public function index($manga_id,$chapter_number){

			$this->pg_connect();

			$rs = @$this->pg_select_obj(
				"a.manga_id,manga_cover_img,manga_others_name,manga_name,chapter_id,chapter_title,chapter_number,chapter_content,chapter_id",
				"chapters b 
					INNER JOIN 
						manga a on(a.manga_id = b.manga_id)
					WHERE 
						chapter_number = {$chapter_number} AND b.manga_id = {$manga_id}"
			)[0];

			if(!$rs){
				return "";
			}

			$chapter_id = $rs["chapter_id"];

			$this->manga_name_url = url_name_replace($rs["manga_name"]);
			$this->manga_id = $manga_id;

			$redis_key = "list_chapters/{$chapter_id}";

			$comments = pg_manga::get_comments($this,$manga_id);

			$this->pg_query(
				"UPDATE 
					chapters 
				SET 
					chapter_views = chapter_views + 1 
				WHERE 
					chapter_id = " . $chapter_id
			);

			if(!myredis::global()->exists($redis_key)){

				$this->pg_connect();

				$rs_chapters = $this->pg_select_obj(
					"chapter_id,chapter_title,chapter_number,chapter_views",
					"chapters 
						WHERE 
							manga_id = " . $manga_id . " order by chapter_number desc"
				);

				$c = $this->chapters($chapter_id,$rs_chapters);

				myredis::global()->set($redis_key,serialize($c));
				myredis::global()->expire($redis_key,500);

				list($list_chapters,$next_chapter,$prev_chapter) = $c;

			}else{

				list($list_chapters,$next_chapter,$prev_chapter) = unserialize(myredis::global()->get($redis_key));
			}

			return [
				"chapter_detail" => $rs,
				"list_chapters"  => $list_chapters,
				"next_chapter"	 => $next_chapter,
				"prev_chapter"	 => $prev_chapter,
				"chapter_id"	 => $chapter_id
			] + $comments;
		}

		private function chapters($ct_id,$chapters){

			$options = "";
			$count   = count($chapters);

			$next_chapter = [];
			$prev_chapter = [];

			$i = 0;

			$selected = "";

			foreach ($chapters as $chapter){
                                                
                $chapter_id     = $chapter["chapter_id"];
                $chapter_number = $chapter["chapter_number"];
                $chapter_title  = "Chapter {$chapter_number}" . ( $chapter["chapter_title"] != "" ?  " : " . $chapter["chapter_title"] : "" );

                if($ct_id == $chapter_id){

                    $selected = 'selected="selected"';

                    $next_chapter = $this->get_chapter(@$chapters[$i - 1]);
                    $prev_chapter = $this->get_chapter(@$chapters[$i + 1]);
                }

                $chapter_url = "/manga/{$this->manga_name_url}-{$this->manga_id}/chapter-{$chapter_number}";

                $options .= "<option {$selected} value=\"{$chapter_url}\">{$chapter_title}</option>";

                $selected = '';

                $i++;
            }

            return [
            	$options,
            	$next_chapter,
            	$prev_chapter
            ];
		}

		private function get_chapter($chapter){

			if(!$chapter){
				return "";
			}

            $chapter_id     = $chapter["chapter_id"];
            $chapter_number = $chapter["chapter_number"];

            $chapter_href = "/manga/{$this->manga_name_url}-{$this->manga_id}/chapter-{$chapter_number}";

            return $chapter_href;
		}
	}
?>