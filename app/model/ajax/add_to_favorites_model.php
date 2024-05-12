<?php
	class add_to_favorites_model extends model{
		
		public function index(){

			$this->pg_connect();

			$user_id = $_SESSION["user"]["id"];
			$manga_id = $_GET["manga_id"];

			$check = $this->pg_select(
				"1",
				"favorites where manga_id = {$manga_id} AND user_id = {$user_id}" 
			);

			if($check){

				$this->pg_query(
					"DELETE FROM favorites where manga_id = {$manga_id} AND user_id = {$user_id}"
				);

				echo json_encode([
					"error" => 0,
					"type"  => "remove"
				]);

			}else{

				$rs = @$this->pg_query(
					"INSERT INTO favorites VALUES({$user_id},{$manga_id},NOW())"
				);

				if($rs){

					echo json_encode([
						"error" => 0,
						"type"  => "add"
					]);

				}else{

					error([
						"message" => "Đã có lỗi xảy ra! Xin hãy thử lại!"
					]);
				}
			}

			myredis::global()->del("fav/manga/" . $user_id  . "-" . $manga_id);
		}
	}
?>