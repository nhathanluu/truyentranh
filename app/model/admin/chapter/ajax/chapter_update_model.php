<?php
    class chapter_update_model extends model{

        public function index($chapter_id){
            
            $chapter_title   = @pg_replace($_POST["chapter_title"]);
            $chapter_number  = $this->chapter_number(@$_POST["chapter_number"]);
            $chapter_content = @pg_replace($_POST["chapter_content"]);

            $this->pg_connect();

            $rs = $this->pg_query(
                "UPDATE 
                    chapters
                SET
                    chapter_title = '{$chapter_title}',
                    chapter_number = {$chapter_number},
                    chapter_content = '{$chapter_content}'
                WHERE 
                    chapter_id = " . $chapter_id
            );

            if($rs){
                echo json_encode([
                    "error"   => 0,
                    "message" => "Cập nhật thành công!"
                ]);
            }
        }

        private function chapter_number($num){

            if(!preg_match('/[0-9\.]+/',$num)){
                error([
                    "message" => "Số thứ tự chương là chữ số"
                ]);
            }

            return $num;
        }
    }
?>