<?php
    class manga_model extends model{

        public function index($manga_id,$url_manga_name){
            $key = "manga2/{$manga_id}";

            $this->pg_connect();

            if(!myredis::global()->get($key)){
                $this->pg_connect();

                $manga = $this->pg_select_obj(
                    "a.manga_id,
                    manga_name,
                    manga_others_name,
                    manga_status,
                    manga_description,
                    manga_updated,
                    manga_cover_img,
                    manga_views,
                    manga_comments
                    ",
                    "manga a 
                        LEFT JOIN 
                            chapters b on(a.manga_id = b.manga_id)
                    WHERE 
                        a.manga_id = " . $manga_id . " "
                )[0];
                if(!$manga){
                    return 0;
                }

                $url_checker = url_name_replace($manga["manga_name"]);

                if($url_checker != $url_manga_name){

                    header("Location:".  SITE_URL . "manga/" . $url_checker . "-" . $manga_id);
                }

                $tags = $this->pg_select_obj(
                    "tag_name,a.tag_id",
                    "tags a
                        inner join 
                            mangas_tags b on(a.tag_id = b.tag_id)
                    where manga_id = " . $manga_id 
                );

                $authors = $this->pg_select_obj(
                    "author_name,a.author_id",
                    "authors a
                        inner join 
                            mangas_authors b on(a.author_id = b.author_id)
                    where manga_id = " . $manga_id 
                );

                $chapters = $this->pg_select_obj(
                    "chapter_id,chapter_title,chapter_number,chapter_views,chapter_date_published",
                    "chapters 
                        WHERE 
                            manga_id = {$manga_id} 
                        ORDER BY
                            chapter_number DESC"
                );

                $comments = pg_manga::get_comments($this,$manga_id);

                $manga["tags"]    = $tags;
                $manga["authors"] = $authors;

                $data = [
                    "manga"    => $manga,
                    "chapters" => $chapters,
                ] + $comments ;



                myredis::global()->set($key, serialize($data));
                myredis::global()->expire($key,200);

            }else{

                $data = unserialize(myredis::global()->get($key));
            }

            return $data;
        }
    }
?>