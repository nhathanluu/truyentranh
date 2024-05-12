<?php
	class update_model extends model{

		public function index($manga_id){
			
			$manga_name 	   = @pg_replace($_POST["manga_name"]);
			$manga_description = @pg_replace($_POST["manga_description"]);
			$status			   = @$_POST["status"] == 0 ? "'f'" : "'t'";
			$manga_others_name = @pg_replace($_POST["others_name"]);

			$this->pg_connect();

			$this->pg_query("BEGIN") or die("Could not start transaction\n");

			$rs = $this->pg_query(
				"UPDATE 
					manga 
				SET 
					manga_name = '{$manga_name}',
					manga_search = '" . convert_to_search_str($manga_name . " " . $manga_others_name) . "',
					manga_description = '{$manga_description}',
					manga_status = {$status},
					manga_others_name = '{$manga_others_name}'
				WHERE 
					manga_id = " . $manga_id
			);

			$rs2 = $this->update_cover_img($manga_name,$manga_id);

			if($rs && $rs2){
 	
				$this->pg_query("COMMIT") or die("Transaction commit failed\n");

				echo json_encode([
					"error"   => 0,
					"message" => "Cập nhật thành công"
				]);

			}else{

				$this->pg_query("ROLLBACK") or die("Transaction rollback failed\n");
			}
		}

		private function update_cover_img($manga_name,$manga_id){

			$manga_cover_img = @$this->pg_select(
				"manga_cover_img",
				"manga where manga_id = " . $manga_id
			)[0][0];

			$path = "img/{$manga_id}/";

			$info_img = getimagesize($path . $manga_cover_img);

			$attr = $info_img[0] . "x" . $info_img[1];

			$url_name = url_name_replace($manga_name);
			$ext = "." . explode(".",$manga_cover_img)[1];

			$new_name = $attr . "-" . $url_name . $ext;

			if(rename($path . $manga_cover_img,$path . $new_name)){

				$rs = $this->pg_query("
					UPDATE 
						manga 
					SET 
						manga_cover_img = '{$new_name}' WHERE manga_id = " . $manga_id);

				if($rs){
					return true;
				}
			}

			return false;
		}
	}
?>