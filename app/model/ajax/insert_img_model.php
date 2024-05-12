<?php
	require "blogger.php";

	class insert_img_model extends model{
		
		public function index(){

			$imgs = explode("\n",@$_POST["imgs"]);

			$content = "";

			foreach ($imgs as $img){
				$content .= $this->upload($img) ."\n";
			}

			$content = trim($content,"\n");

			if($content){

				echo json_encode([
					"error"   => 0,
					"content" => $content
				]);

			}else{

				error();
			}
		}

		private function upload($img){

			$img = @explode("|",$img);

			$url = @trim(remove_ws($img[0]));
			$stt = @trim(remove_ws($img[1]));

			$status = curl::header($url,[
				CURLOPT_REFERER => "http://www.nettruyen.com/"
			]);

			if($status != 200){
				return;
			}

			$file_size = curl::filesize($url,[
				CURLOPT_REFERER => "http://www.nettruyen.com/"
			]);

			if($file_size >= 20000000){
				return;
			}

			if(!is_numeric($stt) 
					|| $stt == ""
						|| !($stt >= 0 && $stt <= 999) 
			){
				return;
			}


			$curl = get_web_page($url,[
				CURLOPT_REFERER => "http://www.nettruyen.com/"
			]);

			if($curl["errno"]){
				return;
			}

			$info_img  = getimagesizefromstring($curl["content"]);

			if(!is_array($info_img)){

				return;
				
			}

			$ext = explode("/",$info_img["mime"])[1];

			$blogger = new blogger();
			$lh3 = $blogger->upload($curl["content"],sprintf("%'03d",$stt) . "." . $ext);

			return $lh3;
		}
	}
?>