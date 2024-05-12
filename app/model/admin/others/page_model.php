<?php
    class page_model extends model{
        
        public function index(){

            $this->pg_connect();

            $congai = @$this->to_str(unserialize(myredis::global()->get("filter/1")));

            if($congai){

                $rs1 = $this->pg_select_obj(
                    "tag_name",
                    "tags 
                        WHERE tag_id IN ($congai)"
                );
            }

            $contrai = @$this->to_str(unserialize(myredis::global()->get("filter/2")));

            if($contrai){

                $rs2 = $this->pg_select_obj(
                    "tag_name",
                    "tags 
                        WHERE tag_id IN ($contrai)"
                );
            }

            return [
                "congai" => @$rs1,
                "contrai" => @$rs2
            ];

        }

        private function to_str($arr){

            $str = "";

            foreach ($arr as $value) {
                $str .= "{$value},";
            }

            return trim($str,",");
        }
    }
?>