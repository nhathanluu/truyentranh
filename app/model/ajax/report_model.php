<?php
    class report_model extends model{
            
        public function index($chapter_id){

            $type    = @$_POST["report_type"];
            $content = @$_POST["report_content"];

            $arr_type = [1,2,3,4];

            if(!in_array($type,$arr_type)){

                echo error([
                    "message" => "Vui lòng chọn thẻ"
                ]);
            }

            $this->pg_connect();

            $rs = @$this->pg_query(
                "INSERT INTO
                    report(r_type,r_content,chapter_id,r_creation_date)
                VALUES
                    ('{$type}','{$content}',{$chapter_id},NOW())"
            );

            if($rs){

                success([
                    "message" => "Cảm ơn đã báo cáo ^^"
                ]);

            }else{

                 echo error([
                    "message" => "Đã có lỗi xảy ra! Xin hãy thử lại"
                ]);
            }
        }
    }
?>