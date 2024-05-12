<?php
    set_include_path($_SERVER['DOCUMENT_ROOT']);
    
    function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

    require_once "init.php";
    require_once "library/simple_html_dom.php";
    require_once "blogger.php";

    class nettruyen_chapter extends model{

        protected $manga_id;

        public function query($url){

            $this->pg_connect();

            $rs = @$this->pg_select(
                "manga_id",
                "crawl where url = '$url'"
            )[0];
            
            if(!$rs){
                print("manga_id doesn't exist \n");
                return ;
            }

            $this->manga_id = $rs[0];

            $content = $this->config_curl($url);

            if(empty($content)){
                print("couldn't curl '" . $url . "' \n");
                return;
            }

            $html = str_get_html($content);
            
           

            $chapters     = $this->config_curl_chapters($url,$html);
            $arr_chapters = $this->handling_chapters($chapters);


            ksort($arr_chapters);

            foreach ($arr_chapters as $c => $chapter) {

                $this->get_chapter($chapter["url"],pg_replace($chapter["chapter_title"]),$c
                );

                print("---------------------------------------\n");
            } 
        }

        protected function handling_chapters($chapters){

            $arr_chapters = $chapters[0];
            $str_id = trim($chapters[1],",");
            
            $r = $this->pg_select(
                "chapter_number",
                "chapters 
                    WHERE 
                        chapter_number 
                    IN({$str_id}) AND manga_id = " . $this->manga_id
            );

            foreach (make_row($r,0) as $value){

                if(array_key_exists($value, $arr_chapters)){

                    unset($arr_chapters[$value]);  
                }
            }

            return $arr_chapters;
        }

        protected function config_curl($url){

            return @get_web_page($url)["content"];
        }

        protected function config_curl_chapters($url,$html){

            $arr_chapters = [];
            $str_id = "";


            $chapters = array_reverse($html->find("div.chapter > a"));

            foreach ($chapters as $chapter) {

                $url            = $chapter->href;
                $chapter_title  = $chapter->innertext;
                    
                $chapter_number = $this->chapter_number($chapter_title);
                
                if($chapter_number){
                    
                    $arr_chapters[$chapter_number]["url"] = $url;
                    $arr_chapters[$chapter_number]["chapter_title"] = $this->chapter_title($chapter_title);
                    $str_id .= $chapter_number . ",";
                }
            } 

            $str_id = trim($str_id,",");

            return [
                $arr_chapters,
                $str_id
            ];
        }

        protected function chapter_number($url){

            if(preg_match('/chapter ([0-9\.]+)/i',$url,$m)){
                
                $t = explode(".",$m[1]);

                if(count($t) > 2){
                    return  (float)$m[1] . $t[count($t) - 1];
                }

                return $m[1];
            }
            
            return;
        }

        protected function chapter_title($chapter_title){

            $t = @explode(":",$chapter_title)[1];
            return $t != "" ? remove_ws($t) : "";
        }

        public function get_chapter(
            $chapter_url,
            $chapter_title,
            $chapter_number
        ){
            
            $this->pg_query("BEGIN");

            print_r("crawling " . $chapter_url  . " .... \n\n");

            $content = $this->config_chapter_url($chapter_url);
       
            if(empty($content)){
                print("couldn't curl " . $chapter_url . "\n");
                return;
            }

            $chapter_id = $this->get_chapter_id();

            $html = str_get_html($content);
            
                 
            

            
            //////////////// HTML DOM ////////////////////////

            $imgs = $this->config_curl_img_src($html);
            
            //var_dump($imgs);die;

            //////////////////////////////////////////////////

            $arr_imgs = [];
            
            $chapter_content = "";
            $images_content  = "";


            $tmp_name =  tmp_name();

            $count = count($imgs);

            $p = 0;

            for($i = 0 ; $i < $count ; $i++){

                $img = $imgs[$i];

                for ($j = 1; $j <= 5; $j++){

                    $curl = $this->config_get_img($img);
                    if ($curl["errmsg"] && $curl["errmsg"] == 'SSL certificate problem: certificate has expired') {
                        $img->src = str_replace('https', 'http', $img->src);
                        //var_dump($img->src);die;
                        $curl = $this->config_get_img($img);
                    }
                    //var_dump($curl);die;

                    if($curl["errno"]){

                        if($curl["errno"] == 28){
                            print "-> Error : Time out '{$img->src}' , recrawl {$j} times \n";
                        }

                        continue;
                    }

                    $img_info = $this->check_img($curl["content"]);
                    //var_dump($img_info);die;

                    if(!$img_info){

                        print "-> Error : '{$img->src}' is not an image , recrawl {$j} times \n";
                        
                    }else{

                        break;
                    }
                }

                if($j == 6){

                    print "Error : '{$img->src}' failed to crawl";

                    file_put_contents("/www/wwwroot/truyen.thietkewebtheomau.com/nettruyen/crawl_errors/{$this->manga_id}.txt",$chapter_url);

                    return;
                }


                $size = $img_info[0] . "x" . $img_info[1];

               

                print "-> '{$img->src}' downloaded successfully \n-> Sending to server ....\n";

                $ext = explode("/",$curl["content_type"])[1];
            

                $send_to_server = $this->send_to_server($this->manga_id, $chapter_id,$curl["content"],$ext,$p, $img->src, $size, $images_content
                );

                if($send_to_server == false){

                    echo "\n->ERROR : Upload failed to -> '{$img_src}' \n";

                    return;

                }else{

                    echo "\n->SUCCESS : Sending is success! \n";
                }

                $p++;
            }

            $images_content = trim($images_content,"\n");

            $re_check =  @$this->pg_select(
                "1",
                "manga 
                    WHERE 
                        manga_id = {$this->manga_id}"
            )[0];

            if(!$re_check){
                print "Manga has been removed!";
                die();
            }
            
            $success = $this->handling_database( $chapter_id, $chapter_title, $images_content, $chapter_number
            );

            if ($success){

                // $commit = $this->commit($this->manga_id,$chapter_id);

                // if($commit){

                    $this->pg_query("COMMIT");
                    print "-> Success : {$chapter_url} inserted \n";
                    
                    myredis::global()->del("manga/{$this->manga_id}");

                // }else{

                //     print $chapter_url  ." failed \n";
                //     $this->pg_query("ROLLBACK");
                // }

            }else{

                print $chapter_url  ." failed \n";
                $this->pg_query("ROLLBACK");
            }

        }

        protected function handling_database(
            $chapter_id,
            $chapter_title,
            $images_content,
            $chapter_number
        ){
            $rs1 = $this->pg_query(
                "INSERT INTO 
                    chapters (
                        chapter_id,
                        chapter_title,
                        chapter_content,
                        manga_id,
                        chapter_number,
                        chapter_date_published
                    )
                VALUES(
                    '{$chapter_id}',
                    '{$chapter_title}',
                    '{$images_content}',
                    '{$this->manga_id}',
                    {$chapter_number},
                    '" . date("Y-m-d H:i:s") . "'
                )"
            );

            $rs2 = $this->pg_query(
                "UPDATE 
                    manga 
                SET 
                    manga_updated = '" . date("Y-m-d H:i:s") . "'
                WHERE 
                    manga_id = {$this->manga_id}"
            );

            if($rs1 && $rs2){

                return true;

            }else{

                return false;
            }
        }

        protected function get_chapter_id(){

            $query_id   = $this->pg_query("select nextval(pg_get_serial_sequence('chapters', 'chapter_id')) as chapter_id;");

            return pg_fetch_row($query_id)[0];
        }

        protected function config_get_img($img){

            return get_web_page(preg_match('/^\/\//', $img->src) ? str_replace("//","",$img->src) : $img->src,[
                CURLOPT_REFERER => "http://www.nettruyentop.com/"
            ]);
        }

        protected function config_chapter_url($chapter_url){
            return get_web_page($chapter_url,[
                CURLOPT_REFERER => "http://www.nettruyentop.com/"
            ])["content"];
        }

        protected function config_curl_img_src($html){
            return $html->find(".page-chapter > img");
        }

        private function commit($manga_id,$chapter_id){

            $data = post_data(SERVER_IMG . "commit.php",[
                "manga_id"   => $manga_id,
                "chapter_id" => $chapter_id
            ]);

            if($data["errno"] || (int)$data["content"] == 0){
                return false;
            }

            return true;
        }

        // manga_id : $manga_id,
        // chapter_id : $chapter_id,
        // raw : $curl["content"],
        // ext : $ext,
        // position : $p,
        // img_src : $img->src,
        // size : $size,
        // images_content : $images_content
        private function send_to_server(
            $manga_id,
            $chapter_id,
            $raw,
            $ext,
            $position,
            $img_src,
            $size,
            &$images_content
        ){

            // upload local
            // $info_img  = getimagesizefromstring($raw);
            // if(!is_array($info_img)){
            //     print " -> Error \n";
            //     return false;
            // }
            // $width  = $info_img[0];
            // $height = $info_img[1];
            // $url_name = $position .'-'. time() .'-' . $chapter_id . "-{$width}x{$height}" . "." . $ext;
            // $dir = ROOT . "img/{$manga_id}/";
            // mkdir($dir,0775);
            // if (!file_put_contents($dir .  $url_name ,$raw)) {
            //     print " -> Error \n";
            //     return false;
            // }
            
            // chmod($dir . $url_name , 0644);
            // $lh3 = "/img/{$manga_id}/".$url_name;
            
            // print " -> Success \n";
            // $images_content .= $lh3 . "\n";
            
            
            // for ($j=1; $j <= 5; $j++){
            //     $blogger = new blogger();
            //     $lh3     = $blogger->upload($raw,sprintf("%'03d",$position) . "." . $ext);
            //     if($lh3){

            //         print " -> Success \n";
            //         $images_content .= $lh3 . "\n";
            //         break;

            //     }else{

            //         $count_test = strlen($curl["content"]);
            //         print " -> Error : Upload failed '{$img_src}' , {$j} times \n";
            //     }

            // }


            $info_img  = getimagesizefromstring($raw);
            if(!is_array($info_img)){
                print " -> Error \n";
                return false;
            }
            $width  = $info_img[0];
            $height = $info_img[1];
            $url_name = $position .'-'. time() .'-' . $chapter_id . "-{$width}x{$height}" . "." . $ext;
            $dir = ROOT . "img/{$manga_id}/";

        //    echo '<pre>';
        //     var_dump($dir);die;
        //     echo $img_src;die;
            $blogger = new blogger();
            $lh3     = $blogger->upload($raw,sprintf("%'03d",$position) . "." . $ext);
            if($j == 7){

                return false;
            }

            return true;
        }

        private function check_img($raw){

            $info_img  = getimagesizefromstring($raw);

            if(!is_array($info_img)){

                return false;  
            }


            return $info_img;
        }
    }
?>