<?php 

	require_once("vendor/autoload.php");
	use ImageKit\ImageKit;

	class imgkit {

		private $imgKit;
		private $rs_upload = [];

		function __construct(){

			$this->imgKit = new ImageKit(
			    "public_1cueiQ2JqCZ/755PKnxDVla+va4=",
			    "private_PTMuuhRiv2g3mCRUQ5UhcL/6h6Y=",
			    "https://ik.imagekit.io/zwt4xz4opf/"
			);
		}

		public function upload($base64,$fileName = "",$unique = false){

			$this->rs_upload = (array)$this->imgKit->uploadFiles(array(
			    "file" 	   			=> $base64,
			    "fileName" 			=> $fileName,
			    "folder"   			=> "thumb/",
			    "useUniqueFileName" => $unique
			));
		}

		public function IK(){
			return $this->imgKit;
		}

		public function RU(){
			return $this->rs_upload;
		}
	}

?>