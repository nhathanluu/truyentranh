<?php

	class curl{ 

		static function header($url,$opts = []){

			$user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

	        $options = array(
	            CURLOPT_USERAGENT 	   => $user_agent,
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_HEADER 	  	   => 1,
	            CURLOPT_NOBODY	       => 1,
	            CURLOPT_REFERER   	   => "https://www.pixiv.net/en/"
	        );
	        $mm = $opts + $options;
	        $ch = curl_init( $url );

	        curl_setopt_array( $ch, $mm );
	        curl_exec($ch);
	        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        curl_close($ch);
	        return $status;
		}

		static function filesize($url,$opts = []){

			$user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

	        $options = array(
	            CURLOPT_USERAGENT      => $user_agent,
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_HEADER         => 1,
	            CURLOPT_NOBODY		   => 1,
	            CURLOPT_REFERER 	   => "https://www.pixiv.net/en/"
	        );

	        $mm = $opts + $options;

	        $ch = curl_init( $url );

	        curl_setopt_array( $ch, $mm );
	        curl_exec($ch);
	        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

	        curl_close($ch);
	        return $size;
		}

		static function send($url , $opts = []){

			$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

	        $options = array(

	            CURLOPT_CUSTOMREQUEST  => "GET",
	            CURLOPT_POST           => false,
	            CURLOPT_USERAGENT      => $user_agent,
	            CURLOPT_RETURNTRANSFER => true, 
	            CURLOPT_HEADER         => false, 
	            CURLOPT_FOLLOWLOCATION => true, 
	            CURLOPT_ENCODING       => "",
	            CURLOPT_AUTOREFERER    => true, 
	            CURLOPT_CONNECTTIMEOUT => 120, 
	            CURLOPT_TIMEOUT        => 20, 
	            CURLOPT_MAXREDIRS      => 10,
	            CURLOPT_REFERER 	   => "https://www.pixiv.net/en/"
	        );

	        $mm = $opts + $options;

	        $ch      = curl_init( $url );
	        curl_setopt_array( $ch, $mm);
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
	}
?>