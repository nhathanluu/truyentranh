<?php
    class user_dashboard_model extends model{

        public function index(){

            $user_id = $_SESSION["user"]["id"];

            $this->pg_connect();
            
            $favorites = $this->pg_select_obj(
                "m.manga_id,manga_name,manga_cover_img",
                "manga m
                    INNER JOIN 
                        favorites f on (m.manga_id = f.manga_id)
                WHERE 
                    f.user_id = " . $user_id . "
                        ORDER BY 
                            f_date desc 
                        LIMIT 
                            5"
            );

            $cmts = $this->pg_select_obj(
                "m.manga_id,manga_name,manga_views,manga_comments,manga_cover_img,cmt_date_published,cmt_content",
                "comments b
                    INNER JOIN 
                        manga m on(b.manga_id = m.manga_id)
                WHERE
                    user_id = " . $_SESSION["user"]["id"] . " AND flag != 't'
                        ORDER BY
                            cmt_date_published desc
                        LIMIT
                            10"
            );

            $info = $this->pg_select_obj(
                "account,nickname,gender",
                "\"user\" 
                    WHERE user_id = " . $user_id
            )[0];

            return [
                "info"      => $info,
                "favorites" => $favorites,
                "cmts"      => $cmts
            ];
        }
    }
?>