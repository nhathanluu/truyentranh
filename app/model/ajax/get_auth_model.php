<?php

	class get_auth_model extends model{
		
		public function index(){

			if(@$_SESSION["user"]){
				success($_SESSION["user"]);
			}
		}
	}
?>