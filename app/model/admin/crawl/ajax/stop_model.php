<?php

    class stop_model extends model{

        public function index(){
            
            session_write_close();

            shell_exec('sh shell/stop.sh');
        }
    }
?>