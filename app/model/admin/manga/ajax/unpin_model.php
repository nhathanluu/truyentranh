<?php
	class unpin_model extends model{

		public function index($manga_id){

			$this->pg_connect();

			$rs = $this->pg_query("DELETE FROM pin WHERE p_manga_id = {$manga_id}");

			if($rs){

				myredis::global()->del("pin2");
				
				success([
					"message" => "Unpinned successfully!"
				]);

			}else{

				error([
					"message" => "Error!"
				]);
			}
		}
	}
?>