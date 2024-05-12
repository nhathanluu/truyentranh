<?php 
class set_tag_model extends model{

      public function index(){

            $type = (int)@$_POST["type"];
            $tag  = @remove_ws(pg_replace($_POST["tag"]));

            if(!in_array($type,[1,2])){
                  error([
                        "message" => "Lỗi"
                  ]);
            }

            $this->pg_connect();

            $tag_id = @$this->pg_select(
                  "tag_id",
                  "tags
                        where tag_name = '{$tag}'"
            )[0][0];

            if(!$tag_id){
                  error([
                        "message" => "{$tag} không tồn tại"
                  ]);
            }


            $key = "filter/{$type}";

            $get = unserialize(myredis::global()->get($key));

            if(!is_array($get)){
                  myredis::global()->set($key,serialize([]));
                  $get = myredis::global()->get($key);
            }

            if($get){
                  
                  if(in_array($tag_id, $get )){

                        error([
                              "message" => "{$tag} đã tồn tại"
                        ]);

                  }else{
                        array_push($get,$tag_id);
                        myredis::global()->set($key,serialize($get));
                  }

            }else{

                  myredis::global()->set($key,serialize([$tag_id]));
            }


      }
}
?>