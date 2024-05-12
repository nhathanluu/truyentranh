<?php
	class genre_model extends model{

		private $tag = [];
		
		public function index($con){

			$this->pg_connect();
		   

			$query = $this->query_handle($con);
			
			if (!empty($con['genre'])) {
			    $list_genres = $this->pg_select_obj(
                    "tag_id",
                    "tags a 
                    WHERE 
                        tag_name = '".$con['genre']."'"
                );
			} else {
			    $list_genres = $this->pg_select_obj(
                    "tag_id",
                    "tags a "
                );
			}
			
            // echo '<pre>';
            // var_dump($test);die;
            $genre_id = $list_genres[0]['tag_id'];
			
            $list_manga_ids = $this->pg_select(
                    "a.manga_id",
                    "manga a 
                        LEFT JOIN 
                            mangas_tags b on(a.manga_id = b.manga_id)
                    WHERE 
                        b.tag_id = '".$genre_id."'
                    GROUP By a.manga_id " 
                           
                );
            $manga_ids = [-1];
            if (!empty($list_manga_ids)) {
                $manga_ids = [];
                foreach ($list_manga_ids as $list_manga_id) {
                    $manga_ids[] = $list_manga_id[0];
                }
            }
            $in = implode(",",$manga_ids);
            

			$count = $this->pg_select(
				"count(*)",
				"manga
					  WHERE 
                        manga_id IN($in)"
			)[0][0];

			$limit = 40;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$order_by = $this->order_by();

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
					)as c2
					group by c2.manga_id
				) as manga_chapters",
				"manga a 
				WHERE
				    a.manga_id IN($in)
				ORDER BY 
					{$order_by}
				LIMIT 
					{$limit} 
				OFFSET 
					{$offset}"
			);
			
			return [
				"manga_list" 	  => $rs,
				"pagi"		 	  => $pagi,
				"page"		 	  => $page,
			] + $this->tag;
		}

// 		public function index($con){

// 			$this->pg_connect();

// 			$query = $this->query_handle($con);
			
// 			var_dump($query);die;

// 			$count = @$this->pg_select(
// 				"count(*)",
// 				"manga
// 					WHERE 1=1
// 				{$query}"
// 			)[0][0];

// 			$limit = 40;
// 			$pagi  = get_pagi($count,$limit,$offset,$page);

// 			$order_by = $this->order_by();

// 			$rs = $this->pg_select_obj(
// 				"manga_name,manga_views,manga_comments,manga_cover_img,a.manga_id,(
// 					select 
// 						json_agg(c2.*)
// 					from (
// 						SELECT manga_id,chapter_id,chapter_number,chapter_date_published
// 							from chapters b
// 						where b.manga_id = a.manga_id
// 							order by chapter_number desc
// 						LIMIT 2 
// 					)as c2
// 					group by c2.manga_id
// 				) as manga_chapters",
// 				"manga a 
// 				WHERE 1=1
// 					{$query}
// 				ORDER BY 
// 					{$order_by}
// 				LIMIT 
// 					{$limit} 
// 				OFFSET 
// 					{$offset}"
// 			);

// 			return [
// 				"manga_list" 	  => $rs,
// 				"pagi"		 	  => $pagi,
// 				"page"		 	  => $page,
// 			] + $this->tag;
// 		}

		private function query_handle($con){

			$query  = "";

			if(isset($con["status"])){

				$query .= "AND manga_status = '"  . ($con["status"] == "ongoing" ? 'f' : 't') .  "'";	

			}
			
			if(isset($con["genre"])){

				$select   = "tag_id,tag_name";
				
				$tag_name = pg_replace($con["genre"]);

				if(@($_GET["page"] <= 1)){
					$select .= ",tag_description";
				}

				$this->tag = @$this->pg_select_obj(
					$select,
					"tags 
						WHERE 
							tag_name = '{$tag_name}'"
				)[0];

				if(!$this->tag){
					throw new Exception("tag_id");
				}

				$tag_id = $this->tag["tag_id"];

				if($query != ""){

					$query .= " AND manga_tags @> ARRAY[{$tag_id}]";

				}else{

					$query .= " AND manga_tags @> ARRAY[{$tag_id}]";
				}

			}

			return $query;
		}

		private function order_by(){

			$order_by = "manga_updated DESC";

			if(isset($_GET["sort"])){

				$sort = $_GET["sort"];

				if($sort == 1){

					$order_by = "manga_views DESC";

				}elseif($sort == 2){

					$order_by = "total_chapters DESC";
				}

			}

			return $order_by;
		}
	}
?>