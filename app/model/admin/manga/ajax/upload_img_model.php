<?php
	class upload_img_model extends model{

		public function index(){

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

            $tmp_img = tmp_name() . "." . $ext;

            file_put_contents("tmp/" . $tmp_img ,file_get_contents($_FILES['file']['tmp_name']));

            echo json_encode([
            	"error" => 0,
            	"tmp"	=> $tmp_img
            ]);
		}
	}
?>