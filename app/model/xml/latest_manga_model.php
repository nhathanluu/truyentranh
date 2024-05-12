<?php

    class latest_manga_model extends model {

        public function index(){

            $this->pg_connect();

            $rs = $this->pg_select_obj(
                "manga_name,manga_views,manga_comments,manga_cover_img,a.manga_id,manga_updated",
                "manga a 
                    ORDER BY
                        manga_updated DESC
                    LIMIT
                        40"
            );

            $html = "";

            foreach ($rs as $m){
                
                $manga_id        = $m["manga_id"];
                $manga_name      = $m["manga_name"];
                $manga_cover_img = SITE_URL . "img/" . $manga_id . "/" . $m["manga_cover_img"];
                $manga_updated   = lastmod($m["manga_updated"]);
                $manga_name_url  = url_name_replace($manga_name);

                $manga_url       = SITE_URL . "manga/" . $manga_name_url . "-" . $manga_id;

$html .= <<<HTML
<url>
<loc>{$manga_url}</loc>
<lastmod>$manga_updated</lastmod>
<image:image>
<image:loc>{$manga_cover_img}</image:loc>
</image:image>
</url>
HTML;
            }

            $l_url = SITE_URL . "latest-manga";
            $l_updated = lastmod($rs[0]["manga_updated"]);

echo <<<HTML
<urlset 
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
<url>
<loc>{$l_url}</loc>
<lastmod>{$l_updated}</lastmod>
</url>
{$html}</urlset>
HTML;

        }
    }
?>