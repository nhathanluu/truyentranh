<?php
	class del_tag_model extends model{

		public function index(){

			$type = (int)@$_POST["type"];
            $tag  = @remove_ws(pg_replace($_POST["tag"]));

            $this->pg_connect();

            $tag_id = @$this->pg_select(
                "tag_id",
                "tags
                    WHERE 
                    	tag_name = '{$tag}'"
            )[0][0];

            if(!$tag_id){
              	error([
                    "message" => "{$tag} không tồn tại"
             	]);
            }

            $key = "filter/{$type}";

            $get = unserialize(myredis::global()->get($key));

            if($get){

            	if(in_array($tag_id,$get)){

            		if (($k = array_search($tag_id, $get)) !== false) {
					    unset($get[$k]);
					}

            		myredis::global()->set($key,serialize($get));
            	}
            }
		}
	}
?>