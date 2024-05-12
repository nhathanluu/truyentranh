<?php
    
    require "init.php";

    $pg = new model();

    $pg->pg_connect();

    $pg->pg_query(
        "UPDATE 
            manga m
        SET 
            manga_views = (
                SELECT 
                    COALESCE(SUM(chapter_views),0)
                FROM 
                    chapters c
                WHERE
                    c.manga_id = m.manga_id
        )"
    );

    $pg->pg_query(
        "UPDATE 
            manga m
        SET 
            manga_comments = (
                SELECT 
                    COALESCE(COUNT(manga_id),0)
                FROM 
                    comments c
                WHERE
                    c.manga_id = m.manga_id
        )"
    );
?>