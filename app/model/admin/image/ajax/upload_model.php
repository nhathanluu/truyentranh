<?php
    class upload_model extends model{

        public function index(){

            $url = @remove_ws($_POST["url"]);

            $curl = @get_web_page($url);

            $img = is_img($curl["content"]);

            if($img){

                $ext      = explode("/",$curl["content_type"])[1];
                $tmp_name = tmp_name() . "." . $ext;

                file_put_contents("/tmp/" . $tmp_name,$curl["content"]);

                $this->pg_connect();

                $path = "images_deleted/" . $tmp_name;

                $rs = $this->pg_query(
                    "with patt as (
                        SELECT {$ext}2pattern(pg_read_binary_file('/tmp/{$tmp_name}')) as pat
                    )
                    INSERT INTO 
                        pat(img_path,pattern,signature)
                    SELECT 
                        '{$path}',
                        shuffle_pattern((SELECT * from patt)),
                        pattern2signature((SELECT * from patt));"
                );


                if($rs){

                    file_put_contents($path,$curl["content"]);


                    success([
                        "message" => "upload successful!"
                    ]);

                }else{
                    
                     error([
                        "message" => "upload failed"
                    ]);
                }

            }else{

                error([
                    "message" => "upload failed"
                ]);
            }
        }
    }
?>