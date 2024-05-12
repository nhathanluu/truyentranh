<?php 

set_include_path($_SERVER['DOCUMENT_ROOT']);
	
ini_set('display_errors', 1);
error_reporting(~0);

require_once "init.php";
require_once "crawl/nettruyen.php";

class xoa_chapter extends model{

    public function query(){

        $this->pg_connect();

        $this->pg_connect();
        $rs = $this->pg_select(
            "chapter_id, manga_id, chapter_number",
            "chapters"
        );

        foreach($rs as $item_chap) {
            // $chapter_id = $item_chap[0];

            $check = $this->pg_select(
                "chapter_id",
                "chapters where manga_id=".$item_chap[1]." and chapter_number=".$item_chap[2]. " and  chapter_id!=".$item_chap[0]
            );
            //var_dump($check);
            if (!empty($check)) {
                
                $this->pg_query("DELETE FROM chapters WHERE chapter_id = "  . $item_chap[0]);
            }
        }
        
         echo 'Done';
        die;

       
       
    }
}

$xoa_chapter = new xoa_chapter();
$xoa_chapter->query();

