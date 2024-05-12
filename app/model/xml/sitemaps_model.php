<?php

    class sitemaps_model extends model {

        public function index(){

            $this->pg_connect();

            $latest_manga = SITE_URL . "latest-manga.xml";
            $genres       = SITE_URL . "genres.xml";

            $lastmod_latest_manga = lastmod($this->lastmod_latest_manga());
            $lastmod_genre        = lastmod($this->lastmod_genre());
            
echo <<<HTML
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<sitemap>
<loc>{$latest_manga}</loc>
<lastmod>{$lastmod_latest_manga}</lastmod>
</sitemap>
<sitemap>
<loc>{$genres}</loc>
<lastmod>{$lastmod_genre}</lastmod>
</sitemap>
</sitemapindex>
HTML;
        }

        private function lastmod_latest_manga(){

            return @$this->pg_select(
                "manga_updated",
                "manga
                    ORDER BY manga_updated DESC LIMIT 1"
            )[0][0];
        }

        private function lastmod_genre(){

            return @$this->pg_select(
                "t_date_modified",
                "tags
                    ORDER BY t_date_modified DESC LIMIT 1"
            )[0][0];
        }
    }
?>