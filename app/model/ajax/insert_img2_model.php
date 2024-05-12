<?php
	require "blogger.php";

	class insert_img2_model extends model{
		
		public function index(){

			if($_SERVER['CONTENT_LENGTH'] > 10000000){
                error([
                    "message" => "maximum file size is 10mb"
                ]);
            }

			if(!@$_FILES){
				error([
					"message" => "Error!"
				]);
			}

			if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {

                error([
                    "message" => "Upload failed with error code " . $_FILES['file']['error']
                ]);
            }

            $info = getimagesize($_FILES['file']['tmp_name']);

            if($info === FALSE){

                error([
                    "message" => "Unable to determine image type of uploaded file"
                ]);
            }

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

            $position = @$_POST["position"];

            if(!is_numeric($position) 
					|| $position == ""
						|| !($position >= 0 && $position <= 999) 
			){
				error([
					"message" => "position >= 0 and position <= 999"
				]);
			}

			$file = file_get_contents($_FILES['file']['tmp_name']);

            $blogger = new blogger();
			$lh3     = $blogger->upload($file,sprintf("%'03d",$position) . "." . $ext);

			if($lh3){
				echo json_encode([
					"error"   => 0,
					"content" => $lh3
				]);
			}
		}
	}
?>