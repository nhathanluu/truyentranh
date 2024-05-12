<?php
    class author_model extends model{

        public function index($author){

            $this->pg_connect();

            $a = @$this->pg_select_obj(
                "author_id,author_name",
                "authors
                    WHERE
                        author_name = '" . pg_replace($author) . "'"
            )[0];

            // var_dump($a);die;

            if(!$a){
                include VIEW . "404/index.phtml";
            }

            $author_id   = $a["author_id"];
            $author_name = $a["author_name"];

            $manga_author_ids = $this->pg_select(
                "manga_id",
                "mangas_authors
                    WHERE
                    author_id =".$author_id
            );

            //$manga_author_ids = [1024];
            $in = implode(",",$manga_author_ids[0]);

            $count = $this->pg_select(
                "count(*)",
                "manga
                    WHERE
                        manga_id IN($in)"
            )[0][0];

            $limit = 40;
            $pagi  = get_pagi($count,$limit,$offset,$page);

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
                        manga_id IN($in) 
                    ORDER BY
                        manga_updated DESC
                    LIMIT 
                        {$limit} 
                    OFFSET 
                        {$offset}"
            );

            return[
                "author_name" => $author_name,
                "manga"       => $rs,
                "page"        => $page,
                "pagi"        => $pagi
            ];
        }
    }
?>