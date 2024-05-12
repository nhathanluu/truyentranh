<?php
    class gender_model extends model{

        public function index($gender){
            
            $this->pg_connect();

            if($gender == "con-gai"){
                $type = 1;
            }else{
                $type = 2;
            }

            $get = @unserialize(myredis::global()->get("filter/{$type}"));

            if($get){

                $in = implode(",",$get);

            }else{

                return [];
            }

            $count = @$this->pg_select(
                "count(*)",
                "manga
                    WHERE 
                        manga_tags && ARRAY[$in]"
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
                    ) as c2
                    group by c2.manga_id
                ) as manga_chapters",
                "manga a 
                    WHERE 
                        manga_tags && ARRAY[$in]
                    ORDER BY
                        manga_updated desc
                    LIMIT 
                        {$limit} 
                    OFFSET 
                        {$offset}"
            );

            return [
                "manga_list" => $rs,
                "pagi"       => $pagi,
                "page"       => $page
            ]; 
        }
    }
?>