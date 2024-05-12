<?php
    class change_cover_img_model extends model{

        public function index($manga_id){

            if(!@$_FILES){
                error([
                    "message" => "Error!"
                ]);
            }

            if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {

                error([
                    "message" => "Kích thước ảnh quá lớn " . $_FILES['file']['error']
                ]);
            }

            $info = getimagesize($_FILES['file']['tmp_name']);

            if($info === FALSE){

                error([
                    "message" => "Tệp tải lên không phải là hình ảnh"
                ]);
            }

            $ext = explode("/",$info["mime"])[1];

            if( 
                ($ext !== "png") && 
                    ($ext !== "jpeg") &&
                        ($ext !== "webp") 
            ){
                error([
                    "Hình ảnh phải thuộc jpeg/png/webp"
                ]);
            }

            $width  = $info[0];
            $height = $info[1];

            $this->pg_connect();

            $manga_name = $this->pg_select(
                "manga_name",
                "manga where manga_id = " . $manga_id
            )[0][0];

            if(!$manga_name){
                return;
            }

            $path = "img/{$manga_id}";

            unlinkr($path);

            if (!is_dir($path)) {

                mkdir($path);
            }


            $cover_img = "{$width}x{$height}-" . url_name_replace($manga_name) . "." . $ext;

            file_put_contents($path . "/" . $cover_img,file_get_contents($_FILES['file']['tmp_name']));

            $this->pg_query(
                "UPDATE 
                    manga 
                SET 
                    manga_cover_img = '{$cover_img}'
                WHERE 
                    manga_id = {$manga_id}"
            );

            echo json_encode([
                "error"     => 0,
                "cover_img" => "/" . $path . "/" . $cover_img
            ]);
        }

    }
?>