<?php

    class genres_model extends model {

        public function index(){

            $this->pg_connect();

            $rs = $this->pg_select_obj(
                "tag_name,t_date_modified",
                "tags
                    ORDER BY t_date_modified DESC"
            );

            $link_genre = SITE_URL . "genre"; 
            $genre_modified = lastmod($rs[0]["t_date_modified"]);

$html = <<<HTML
<url>
<loc>{$link_genre}</loc>
<lastmod>{$genre_modified}</lastmod>
</url>
HTML;

            foreach ($rs as $t){

                $tag_name      = $t["tag_name"];
                $date_modified = lastmod($t["t_date_modified"]);

                $genre_url = SITE_URL . "genre/" . urlencode(ucname($tag_name));

$html .= <<<HTML
<url>
<loc>{$genre_url}</loc>
<lastmod>{$date_modified}</lastmod>
</url>
HTML;
            }

echo <<<HTML
<urlset 
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">{$html}</urlset>
HTML;
        }
    }
?>