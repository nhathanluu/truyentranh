<?php
    class update_profile_model extends model{

        public function index(){

            $nickname = $this->check_nickname(@$_POST["nickname"]);
            $gender   = $this->gender(@$_POST["gender"]);

            if($_SERVER['CONTENT_LENGTH'] > 5000000){
                error([
                    "message" => "maximum file size is 5mb"
                ]);
            }

            $set_avatar = "";
            $arr_avatar = [];

            if(@$_FILES){

                $avatar = $this->file_upload_handling();

                $set_avatar = ",avatar = '{$avatar}' ";

                $arr_avatar["new_avatar"] = $avatar;

                $_SESSION["user"]["avatar"] = $avatar;
            }

            $rs = $this->pg_query(
                "UPDATE 
                    \"user\" 
                SET 
                    nickname = '{$nickname}',
                    gender = {$gender}
                    {$set_avatar}
                WHERE 
                    user_id = " . $_SESSION["user"]["id"]
            );

            $_SESSION["user"]["nickname"] = $nickname;

            if($rs){

                echo json_encode([
                    "error"   => 0,
                    "message" => "Success!"
                ] + $arr_avatar);

            }else{

                error([

                    "message" => "Please try later!"
                ]);
            }
        }

        private function file_upload_handling(){

            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {

                error([
                    "message" => "Upload failed with error code " . $_FILES['file']['error']
                ]);
            }

            $info = getimagesize($_FILES['avatar']['tmp_name']);

            if ($info === FALSE){

                error([
                    "message" => "Unable to determine image type of uploaded file"
                ]);
            }

            return $this->create_img($info,$_FILES['avatar']['tmp_name']);
        }

        private function create_img($info,$tmp){

            $ext = explode("/",$info["mime"])[1];

            if( 
                ($ext !== "png") && 
                    ($ext !== "jpeg") &&
                        ($ext !== "webp") 
            ){
                error([
                    "Not a jpeg/png/webp"
                ]);
            }

            list($width, $height) = $info;

            if ($width > $height){

                $y = 0;
                $x = ($width - $height) / 2;
                $smallestSide = $height;

            }else{

                $x = 0;
                $y = ($height - $width) / 2;
                $smallestSide = $width;
            }

            $create_from = "imagecreatefrom" . $ext;
            $imageby     = "image" . $ext;
            $img         = $create_from($tmp);

            $thumbSize = 100;
            $thumb     = imagecreatetruecolor($thumbSize, $thumbSize);
            imagecopyresampled($thumb,$img , 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

            $user_id = $_SESSION["user"]["id"];
            $account = str_replace(" ","",$_SESSION["user"]["account"]);

            $dir_path = "avatar/" . $user_id;

            rrmdir($dir_path);

            if(!is_dir($dir_path)){
                
                mkdir($dir_path,0755);  
            }

            $img_path = $dir_path . "/" . $account . "-" . tmp_name() . "." . $ext;

            $imageby($thumb,$img_path);

            return "/" . $img_path;
        }

        private function check_nickname($nickname){

            $len = strlen($nickname);

            if($len < 3){
                error([
                    "message" => "Nickname length >= 3 characters"
                ]);
            }

            if($len < 3){
                error([
                    "message" => "Nickname length <= 40 characters"
                ]);
            }

            return pg_replace(htmlentities($nickname,ENT_QUOTES));
        }

        private function gender($b){

            $gender = 'NULL';

            if(is_numeric($b)){
                
                if($b == 0){

                    $gender = "'f'";

                }else{
   
                    $gender = "'t'";
                }
            }

            return $gender;
        }
    }

?>