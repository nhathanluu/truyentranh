<?php
    class myredis {

        private static $redis;

        static function global(){

            if (!self::$redis){

                self::$redis = new Redis();
                self::$redis->connect('truyentranh-redis', 6379);
            }
            
            return self::$redis;
        }
    }
?>