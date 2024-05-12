<?php

    spl_autoload_register(function($class) {

        $class = strtolower($class);


       

        if(file_exists(LIB.$class.'.php')){

            require_once(LIB.$class.'.php');

        }elseif(file_exists(CONTROLLER.$class.'.php')){

            require_once(CONTROLLER.$class.'.php');

        }elseif(file_exists(MODEL.$class.'.php')){
            
            require_once(MODEL.$class.'.php');

        }else {
           echo LIB.$class.'.php';
            die($class . ' Không có trong hệ thống');
        }
    });
    
    function clean123($string){

        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '_', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities(vn_to_str($string), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    function clean($string) {
       return preg_replace('/[^A-Za-z0-9]/', ' ', $string);
    }

    function replace_quot($str){
        $s = str_replace("'","&apos;", $str);
        $s = str_replace('"',"&quot;", $s);

        return $s;
    }

    function replace_quot2($str){
        $s = str_replace("'"," ", $str);
        $s = str_replace('"'," ", $s);

        return $s;
    }

    function filter_params($key){

        if(!$_GET){
            return "";
        }

        $url_par = "";
        
        foreach ($_GET as $k => $v){
            
            if($key == $k || $k == "page"){
                continue;
            }

            $url_par .= "{$k}={$v}&";
        }

        return rtrim("&" . $url_par,"&");
    }

    function img_path_decode_arr($img_id){

        $md5 = $img_id;

        return [
            substr($t,0,2),
            substr($t,2,2)
        ];
    }
    
    function CURL_IMG($url,$timeout = 20){
            
        $data = [];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout); 
    
        
        $data["raw"] = curl_exec($ch);

        if (curl_errno($ch)){

            $data["error"] = curl_errno($ch);

        }else{

            $info              = curl_getinfo($ch);
            $data["file_size"] = $info["size_download"];
            $data["ext"]       = explode("/",$info["content_type"])[1];
        }

        curl_close($ch);

        return $data;
    }

    function replace_file_name($string){

       return preg_replace('/[^A-Za-z0-9\- ]/','',str_replace('x27','',html_entity_decode($string)));
    }

    function remove_sc($string){

        return preg_replace('/[^A-Za-z0-9\-:_&\. ]/','', $string);
    }

    function remove_ws($str){

        return trim(preg_replace('/\s+/', ' ',$str));
    }

    function get_file_size($clen){

        $size = $clen;

        if ($clen < 1024) {

            $size = $clen .' B';

        }elseif($clen < 1048576){

            $size = round($clen / 1024, 2) .' KB';

        }elseif($clen < 1073741824){

            $size = round($clen / 1048576, 2) . ' MB';

        }elseif($clen < 1099511627776){

            $size = round($clen / 1073741824, 2) . ' GB';
        }
        
        return $size;
    }

    function remove_hack($str){
        
        if (!$str) {
            return;
        }
        return html_chars(stripslashes(trim(urldecode($str))));
    }

    function html_chars($str){

        return str_replace(array('<','>','"',"'",chr(92),chr(39)), array('&lt;','&gt;','&quot;','&#x27;','&#92;','&#39'), $str);
    }

    function create_salt($length = 10) {

        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';

        for ($i = 0; $i < $length; $i++) {
            
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    function make_row($arr,$col){

        $row_arr = [];

        for ($i=0; $i < count($arr); $i++) { 

            array_push($row_arr,$arr[$i][$col]);
        }

        return $row_arr;
    }

    function url_name_replace($string){

        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities(vn_to_str($string), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    function vn_to_str ($str){
 
        $unicode = array(
         
        'a' =>  'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
         
        'd' =>  'đ',
         
        'e' =>  'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
         
        'i' =>  'í|ì|ỉ|ĩ|ị',
         
        'o' =>  'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
         
        'u' =>  'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
         
        'y' =>  'ý|ỳ|ỷ|ỹ|ỵ',
         
        'A' =>  'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
         
        'D' =>  'Đ',
         
        'E' =>  'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
         
        'I' =>  'Í|Ì|Ỉ|Ĩ|Ị',
         
        'O' =>  'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
         
        'U' =>  'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
         
        'Y' =>  'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
         
        );
         
        foreach($unicode as $nonUnicode=>$uni){
         
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
         
        return $str;
         
    }

    function get_pagi($count,$limit,&$offset,&$cur_page = ""){

        if(!$count){
            
            $offset = 0;
            return;
        }

        $total_page = ceil($count/$limit);

        if (isset($_GET["page"])) {
            
            $cur_page = (int)$_GET["page"];

            if ($cur_page > 0) {

                if ($cur_page > $total_page){

                    $cur_page = $total_page;
                }

            }else{

                $cur_page = 1;
            }

        }else{

            $cur_page = 1;
        }

        $start     = ($cur_page - 1) * $limit;

        $pages  = [];
        $button = [
            "next" => 0,
            "prev" => 0
        ];
        $j = 0;

        if($cur_page > 1){

            $button["prev"] = ($cur_page - 1);
        }

        if ($cur_page - 2 > 0 && $cur_page + 2 <= $total_page) {

            for($i = $cur_page - 2 ; $i <= $cur_page + 2 ; $i++){

                $pages[$j]["page"] = $i;

                if($i == $cur_page){
                    $pages[$j]["act"] = true;
                }

                $j++;
            }

        }else{

            $start_i = 0;
            $max_i   = 5;

            if($cur_page - 2 <= 0) {

                $start_i = 1;

            }else{

                if ($total_page - 4 <= 0) {
                    
                    $start_i = 1;

                }else{

                    $start_i = $total_page - 4;
                }
            }

            if($cur_page - 2 <= 0){

                if($max_i > $total_page){

                    $max_i = $total_page;

                }else{

                    $max_i = 5;
                }

            }else{

                $max_i = $total_page;
            }

            for($i = $start_i ; $i <= $max_i ; $i++){

                $pages[$j]["page"] = $i;

                if($i == $cur_page){
                    $pages[$j]["act"] = true;
                }

                $j++;
            }
        }

        if($cur_page < $total_page){

            $button["next"] = $cur_page + 1;
        }

        $offset = $start;

        return [
            "pages"  => $pages,
            "button" => $button
        ];
    }

    function in_boolean_mode($keyword){

        $keyword = explode(" ", $keyword);

        foreach ($keyword as &$value){

            if (strlen($value) < 3) {
                
                continue;  
            }

            $value = "+" . $value;
        }

        $keyword = implode(" ", $keyword);

        return $keyword;
    }

    function meta_keywords($keywords,$concat){

        $keywords = explode(",",$keywords);

        for ($i = 0; $i < count($keywords); $i++) { 
            
            $keywords[$i] = $keywords[$i] . " " . $concat;
        }

        return implode(",",$keywords);
    }
    
    function getUrl(){

        if (!isset($_SERVER['REQUEST_URI'])){

            $url = $_SERVER['REQUEST_URI'];

        }else{

            $url = $_SERVER['SCRIPT_NAME'];
            $url .= (!empty($_SERVER['QUERY_STRING']))? '?' . $_SERVER[ 'QUERY_STRING' ] : '';
        }

        return $url;
    }

    function write_img($p,$str){
        
        $fp = fopen($p,'c');
        fwrite($fp,$str);
        fclose($fp);
    }

    function time_elapsed_string($rtime){

        $time = strtotime($rtime);
        
        $time = time() - $time;
        $time = ($time < 1) ? 1 : $time;
        
        if($time >= 26000000){
            return date('d-m-Y', strtotime($rtime));
        }

        $tokens = array (
            86400 => 'ngày',
            3600 => 'giờ',
            60 => 'phút',
            1 => 'giây'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'':'') . " trước";
        }
    }

    function post_data($url,$data){

        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_USERAGENT      => $user_agent,
            CURLOPT_POST           => count($data),
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => false,

        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }
    
    function ucname($string){

        $string = ucwords(strtolower($string));

        foreach (array('-', '\'') as $delimiter){

            if(strpos($string, $delimiter)!==false){

                $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
            }
        }
        
        return $string;
    }

    function get_web_page($url,$opts =[]){

        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $options = $options + $opts;

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    function url_without_get(){
        return strtok($_SERVER["REQUEST_URI"], '?');
    }

    function tmp_name(){
        return round(microtime(true) * 10000);
    }

    function getsize($img_preview){

        $ex = explode("-",$img_preview);
        $ex = explode(".",end($ex));

        return explode("x",$ex[0]);
    }

    function pg_replace($v){
        return str_replace("'","''",$v);
    }

    function lastmod($date){
        
        $datetime = new DateTime($date);
        return $datetime->format('Y-m-d\TH:i:sP');
    }

    function cur_url(){
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        return $actual_link;
    }

    function error($e = []){

        $e["error"] = 1;
        die(json_encode($e));
    }


    function success($e = []){

        $e["error"] = 0;
        echo json_encode($e);
    }

    function die_header($location = "/"){
        header("Location:{$location}");
        die();
    }


    function filerp($model){
        return str_replace("-","_",$model);
    }

    function removeParam($url, $param) {
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*$/', '', $url);
        $url = preg_replace('/(&|\?)'.preg_quote($param).'=[^&]*&/', '$1', $url);
        return $url;
    }

    function grid_fetch($arr){

        $html = "";

        foreach ($arr as $value){
            $html .= $value;
        }

        return $html;
    }

    function _404_page(){

        include VIEW . "404/index.phtml";
        die();
    }

    function cur_url_without_paras(){
        return $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] 
     . explode('?', $_SERVER['REQUEST_URI'], 2)[0];
    }

    function unlinkr($dir, $pattern = "*") {

        $files = glob($dir . "/$pattern"); 

        foreach($files as $file){ 
     
            if (is_dir($file) and !in_array($file, array('..', '.')))  {

                unlinkr($file, $pattern);

                rmdir($file);

            } else if(is_file($file) and ($file != __FILE__)) {

                unlink($file); 
            }
        }
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") 
                   rrmdir($dir."/".$object); 
                else unlink   ($dir."/".$object);
            }
        }
            reset($objects);
            rmdir($dir);
        }
    }

    function dashboard_bc($arr){
        
        return html::breadcrumb($arr,[
            "title" => "Bảng điều khiển",
            "href" => "/admin/dashboard",
            "name" => "Bảng điều khiển"
        ]);
    }

    function now(){
        return date("Y-m-d H:i:s");
    }

    function get_size($manga_cover_img){

        if(preg_match('/([0-9]+x[0-9]+)/',$manga_cover_img,$m)){

            $_ex  = explode("x",$m[1]);

            return [
                0 => [
                    "w" => $_ex[0],
                    "h" => $_ex[1]
                ],
                1 => " width=\"{$_ex[0]}\" height=\"{$_ex[1]}\""
            ]; 
        }

        return "";
    }

    function rm_sp($str){
        return preg_replace('/[^\w\s]+/u', ' ', $str);
    }


    function is_img($raw){

        $info_img  = @getimagesizefromstring($raw);

        if(!is_array($info_img)){

            return false;  
        }

        return $info_img;
    }


    function convert_to_search_str($str){
        return 
        trim(
            remove_ws(

                rm_sp(

                    vn_to_str(

                        replace_quot2($str)
                        
                    )
                )
            )
        );
    }

    //////////////////////////

    
?>