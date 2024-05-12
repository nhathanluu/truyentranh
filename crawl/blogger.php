<?php
    class blogger{

        private function curl($url,$post_data = ""){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => $post_data,
                CURLOPT_HTTPHEADER     => array(
                    'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
                    'Accept: */*',
                    'Accept-Language: en-US,en;q=0.5',
                    'Content-control: no-cache',
                    'X-Same-Domain: explorer',
                    'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
                    'Origin: https://www.blogger.com',
                    'DNT: 1',
                    'Alt-Used: www.blogger.com',
                    'Connection: keep-alive',
                    'cookie: NID=511=Y9yQZwKS37kRQkuou4b5pb7p426UsV4KXASn1Jb8C7xsPeVUZRrhZcXQKdET8tDVRY1Ep9RUNN1_tlaTSTq8dRd1q72YWvNYyN9efwvPd6zA3ELiEZiXymGYp57fQuNYKn6Uyfvk1cW4DmAhAjxeV6e2cPrmgSwMSdXxM8U_QfeRV7qfldJuB6veK1rtXn0Rnd2kozcUflKAXKPD5EeLFLgrIWVWSfPRW8IZSg; OTZ=6307496_28_28__28_; SID=FggwwXEIu_VFMZGVvavpY826M2q2M0E9QTLQ4pjZSGuGo-yYR0zqReUdHntBpLs6hzuw_w.; __Secure-1PSID=FggwwXEIu_VFMZGVvavpY826M2q2M0E9QTLQ4pjZSGuGo-yYGeUIRJnClhLtV7NcFja7gA.; __Secure-3PSID=FggwwXEIu_VFMZGVvavpY826M2q2M0E9QTLQ4pjZSGuGo-yY715Lywy33ew9faMIJWm5eg.; HSID=AVw0Rq0tirzaK_Ypv; SSID=A1Y_wJWuG-y8Rjqsk; APISID=NKnwIcjFNnm9weI5/AvRwKR0BS0byPSnml; SAPISID=emdodOX09QvW9qYd/AgBK715r15w2I1D24; __Secure-1PAPISID=emdodOX09QvW9qYd/AgBK715r15w2I1D24; __Secure-3PAPISID=emdodOX09QvW9qYd/AgBK715r15w2I1D24; S=blogger=4xvWdRDT3mpgp4i0qzJlx7JY2WrtuJOMeWMBhvNoqGY',
                    'referer: https://www.blogger.com/picker?nonce=dLTM%2BiG3pRYQXrkTgKBTNg&protocol=gadgets&origin=https%3A%2F%2Fwww.blogger.com&authuser=0&rpcUrl=https%3A%2F%2Fwww-rpcjs-opensocial.googleusercontent.com%2Fgadgets%2Fjs%2Frpc.js%3Fc%3D1%26container%3Dblogger&hl=vi&parent=https%3A%2F%2Fwww.blogger.com%2Frpc_relay.html&thumbs=orig&multiselectEnabled=true&hostId=blogger&title=Th%C3%AAm%20h%C3%ACnh%20%E1%BA%A3nh&uploadToAlbumId=7046983838178463553&pp=%5B%5B%22blogger%22%2C%7B%22albumId%22%3A%227046983838178463553%22%2C%22copyFromPicasa%22%3Atrue%7D%5D%5D&nav=((%22photos%22%2C%22T%E1%BA%A3i%20l%C3%AAn%22%2C%7B%22mode%22%3A%22palette%22%2C%22allowedItemTypes%22%3A%22photo%22%2C%22hideBc%22%3A%22true%22%2C%22upload%22%3A%22true%22%2C%22data%22%3A%7B%22silo_id%22%3A%223%22%7D%2C%22parent%22%3A%227046983838178463553%22%7D))&rpcService=9vzwbbqwry7&rpctoken=8t9aoz9q64fo'
                )
            ));

            echo $url;

            $response = curl_exec($curl);
            // var_dump($response);die;

            curl_close($curl);
            return $response;
            
        }

        private function curl_s3($url,$post_data = ""){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => 'https://s3.thietkewebtheomau.com/upload-form.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => '',
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => $post_data,
                CURLOPT_HTTPHEADER     => array(
                    'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
                    'Accept: */*',
                    'Accept-Language: en-US,en;q=0.5',
                    'Content-control: no-cache',
                    'X-Same-Domain: explorer',
                    'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
                )
            ));

            echo '<pre>';
            var_dump($post_data);

            $response = curl_exec($curl);
            var_dump($response);

            curl_close($curl);
            return $response;
            
        }

        public function upload($img,$file_name){

            $this->file_size = strlen($img);
            $this->file_name = $file_name;

            //$token_url = $this->get_token();


            // $upload_image = $this->curl_s3($token_url,$img);
            // // var_dump($upload_image);die;
            // // var_dump($this->get_url_img($upload_image));die;

            // if($upload_image != ""){
            //     return $this->get_url_img($upload_image);
            // }else{
            //     return 0;
            // }

           
       
            $upload_image = $this->curl_s3('https://s3.thietkewebtheomau.com/upload-form.php',$file_name);
            // var_dump($upload_image);die;
            // var_dump($this->get_url_img($upload_image));die;

            if ($upload_image != ""){
                return $this->get_url_img($upload_image);
            } else{
                return 0;
            }
        }

        private function get_url_img($json){

            $url = @json_decode($json,1)["sessionStatus"]["additionalInfo"]["uploader_service.GoogleRupioAdditionalInfo"]["completionInfo"]["customerSpecificInfo"]["url"];

            if(!$url){
                return "";
            }

            $ex = explode("/",$url);

            $r = $ex[count($ex)-1];

            return str_replace($r,"s0/" . $r,$url);
        }

        private function get_token(){

            $time = strtotime(date("d-m-Y h:i:s"));

            $post_data = '{"protocolVersion":"0.8","createSessionRequest":{"fields":[{"external":{"name":"file","filename":"' . $this->file_name  . '","put":{},"size":' . $this->file_size .'}},{"inlined":{"name":"use_upload_size_pref","content":"true","contentType":"text/plain"}},{"inlined":{"name":"streamid","content":"blogger","contentType":"text/plain"}},{"inlined":{"name":"disable_asbe_notification","content":"true","contentType":"text/plain"}},{"inlined":{"name":"silo_id","content":"3","contentType":"text/plain"}},{"inlined":{"name":"title","content":"' . $this->file_name  .'","contentType":"text/plain"}},{"inlined":{"name":"addtime","content":"' .$time . '","contentType":"text/plain"}},{"inlined":{"name":"c189022504","content":"true","contentType":"text/plain"}},{"inlined":{"name":"batchid","content":"' . $time . '","contentType":"text/plain"}},{"inlined":{"name":"album_id","content":"7052010136552979857","contentType":"text/plain"}},{"inlined":{"name":"album_abs_position","content":"145","contentType":"text/plain"}},{"inlined":{"name":"client","content":"blogger","contentType":"text/plain"}}]}}';

            //echo $post_data;die;
            $p  = $this->curl('https://www.blogger.com/upload/blogger/photos/resumable?authuser=0',$post_data);

            $rs  = json_decode($p,1);
            $url = @$rs["sessionStatus"]["externalFieldTransfers"][0]["putInfo"]["url"];

            return $url;
        }
    }
?>