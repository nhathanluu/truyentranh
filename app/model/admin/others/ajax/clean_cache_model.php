<?php
	class clean_cache_model extends model{

		public function index(){

			myredis::global()->del("pin2");
            myredis::global()->del("ranking2");
		}
	}
?>