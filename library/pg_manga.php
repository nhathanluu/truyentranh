<?php
    class pg_manga extends model{   
        
        static function get_comments($t,$manga_id){

            $count = @$t->pg_select(
                "count(*)",
                "comments where manga_id = " . $manga_id . " AND parent_cmt_id is null"
            )[0][0];

            $limit = 15;
            $pagi  = get_pagi($count,$limit,$offset);

            $cmts = $t->pg_select_obj(
                "cmt_id,cmt_content,nickname,cmt_date_published,avatar,chapter_number,flag,(
                    SELECT json_agg(c.*)
                        FROM (
                            SELECT 
                                cmt_id,cmt_content,nickname,cmt_date_published,avatar,chapter_number
                            FROM 
                                comments c
                                    INNER JOIN 
                                        \"user\" sa on (sa.user_id = c.user_id)
                                    LEFT JOIN 
                                        chapters sct on (sct.chapter_id = c.chapter_id)
                            WHERE 
                                b.cmt_id = c.parent_cmt_id
                            ORDER BY 
                                cmt_id 
                                    DESC
                            LIMIT
                                3 
                        ) as c
                ) as child_cmts,(
                    SELECT 
                        count(*) 
                    FROM 
                        comments count
                    WHERE
                        count.parent_cmt_id = b.cmt_id
                ) as count",
                "comments b
                    INNER JOIN 
                        \"user\" a on (a.user_id = b.user_id)
                    LEFT JOIN 
                        chapters ct on (ct.chapter_id = b.chapter_id)
                WHERE
                    b.manga_id = {$manga_id} AND parent_cmt_id is null 
                ORDER BY 
                    cmt_id 
                        DESC 
                LIMIT
                    $limit
                OFFSET
                    $offset"
            );

            return [
                "comments" => $cmts,
                "pagi"     => $pagi
            ];
        }
    }
?>