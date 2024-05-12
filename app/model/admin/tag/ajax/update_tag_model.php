<?php
    class update_tag_model extends model{

        public function index($tag_id){

            $tag_description = pg_replace(@$_POST["description"]);

            $rs = $this->pg_query(
                "UPDATE 
                    tags
                SET
                    tag_description = '{$tag_description}',
                    t_date_modified = '" . date("Y-m-d H:i:s") . "'
                WHERE
                    tag_id = {$tag_id}"
            );

            if($rs){
                
                success([
                    "message" => "Cập thành thành công!"
                ]);

            }else{

                error([
                    "message" => "Lỗi , xin hãy thử lại!"
                ]);
            }
        }
    }
?>

