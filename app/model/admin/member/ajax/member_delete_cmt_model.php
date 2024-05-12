<?php
    class member_delete_cmt_model extends model{

        public function index($cmt_id){
            

            $checker = @$this->pg_select(
                "1",
                "comments 
                    WHERE 
                        parent_cmt_id = {$cmt_id}
                    LIMIT
                        1"
            )[0][0];

            if($checker){

                $this->pg_query(
                    "UPDATE
                        comments 
                    SET 
                        flag = 't'
                    WHERE 
                        cmt_id = " . $cmt_id
                );
                
                die(json_encode([
                    "error"   => 0,
                    "message" => "Xóa thành công!"
                ]));
            }

            $rs = $this->pg_query(
                "WITH p AS (
                    DELETE FROM 
                        comments 
                    WHERE 
                        cmt_id = " . $cmt_id . " RETURNING parent_cmt_id
                )
                DELETE FROM comments where cmt_id = (
                        SELECT 
                            parent_cmt_id
                        FROM 
                            comments 
                        WHERE 
                            parent_cmt_id = 
                                            ( 
                                                SELECT 
                                                    parent_cmt_id 
                                                        FROM p 
                                            ) 
                        GROUP BY 
                            parent_cmt_id
                        HAVING COUNT(*) = 1 ) and flag = 't'
                "
            );


            if($rs){

                echo json_encode([
                    "error"   => 0,
                    "message" => "Xóa thành công!"
                ]);

            }else{

                error();
            }
        }
    }
?>