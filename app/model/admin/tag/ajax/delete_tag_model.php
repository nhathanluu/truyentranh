<?php
    class delete_tag_model extends model{

        public function index($tag_id){

            $this->pg_query("BEGIN");

            $rs = $this->pg_query(
                "WITH p AS (
                    DELETE FROM mangas_tags WHERE tag_id = {$tag_id} RETURNING manga_id
                )
                UPDATE 
                    manga m1
                SET 
                    manga_tags = sub.arr_tag
                FROM 
                    (
                        SELECT 
                            p.manga_id,array_agg(tag_id) as arr_tag
                        FROM p
                            INNER JOIN 
                                mangas_tags mt ON (mt.manga_id = p.manga_id)
                        WHERE
                            tag_id != {$tag_id}
                        GROUP BY 
                            p.manga_id
                    ) as sub
                WHERE 
                    m1.manga_id = sub.manga_id;
                DELETE FROM
                    tags
                WHERE
                    tag_id = {$tag_id}"
            );

            if($rs){
                
                $this->pg_query("COMMIT");

                require "genre.php";

                success([
                    "message" => "Xóa thành công!"
                ]);

            }else{

                error();
            }
        }
    }
?>

