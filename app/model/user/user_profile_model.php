<?php

	class user_profile_model extends model {

		public function index(){

			$rs = @$this->pg_select_obj(
				"gender,account,nickname,avatar",
				"\"user\" 
				WHERE 
					user_id = " . $_SESSION["user"]["id"] 
			)[0];

			return $rs;
		}
	}
?>