<?php

	class get_fav_model extends model{
		
		public function index($manga_id){

			$fav = "fav/manga/" . $_SESSION["user"]["id"] . "-" . $manga_id;

			if(!myredis::global()->exists($fav,"")){

				$this->pg_connect();

				$is_fav = @$this->pg_select(
					"1",
					"favorites
						WHERE 
							manga_id = {$manga_id} AND user_id = " . $_SESSION["user"]["id"]
				)[0][0];

				myredis::global()->set($fav,$is_fav);
				myredis::global()->expire($fav,1440);

			}else{

				$is_fav = myredis::global()->get($fav);
			}

			success([
				"is_fav" => (int)$is_fav
			]);
		}
	}
?>