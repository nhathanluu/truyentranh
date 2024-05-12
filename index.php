<?php

// echo phpinfo();die;

require_once("init.php");

/// MAIN PAGE ------------------------------------

$route[""] = "main/index";
$route["moi-nhat"] = "main/latest_manga";
$route["moi-cap-nhat(.+?)"] = "main/latest_manga_paginate";

$route["manga/([a-zA-Z0-9\-]+)-([0-9]+)"] = "main/manga";

$route["lich-su"] = "main/history";
$route["tim-truyen"] = "search/page";

$route["(con-gai|con-trai)"] = "main/gender";

$route["manga/([a-zA-Z0-9\-]+)-([0-9]+)/chapter-([0-9\.]+)"] = "main/chapter";
$route["manga/(.*?)/chapter-([0-9\.]+)/([0-9]+)"]            = "main/chapter_header";

$route["(da-hoan-thanh|dang-tien-hanh)"] = "genre/status";

$route["(sitemaps|latest-manga|genres)\.xml"] = "xml/index";

/////////// MAIN -> GENRE

$route["the-loai"]                                        = "genre/index";
$route["the-loai/(.+?)/*(da-hoan-thanh|dang-tien-hanh)*"] = "genre/genre";

/////////// MAIN -> AUTHOR

$route["author/(.+?)"] = "author/index";

/////////// MAIN -> fad

$route["search"] = "search/index";
$route["search/tag"] = "search/tag";

$route["ajax/manga/add-to-favorites"]    = "ajax/manga/add_to_favorites";
$route["ajax/(insert-img|insert-img2)"]  = "ajax/admin/index";
$route["ajax/(report|get-fav)/([0-9]+)"] = "ajax/main/index";

/////////// MAIN -> COMMENTS

$route["ajax/comment/(add|reply|remove)"]  = "comment/ajax_comment/index";
$route["ajax/comment/(get|show)/([0-9]+)"] = "comment/ajax_get_comments/index";

$route["ajax/(get-auth)"] = "ajax/main/index";

/// LOGIN - REGISTER - LOGOUT --------------------

$route["(dang-ky|dang-nhap|logout)"] = "user/account/index";

/// MEMBER PAGE ----------------------------------

$route["member/(dashboard|profile|change-password|comments|favorites)"] = "user/wall/index";
$route["member/ajax/(update-profile|change-password)"]                  = "user/ajax_user/index";

/// ADMIN PAGE -----------------------------------

/////////// ADMIN -> DASHBOARD

$route["admin/dashboard"] = "admin/dashboard/index";

/////////// ADMIN -> MEMBER

$route["admin/member/(list|comments)/*([0-9]*)"] = "admin/member/page";

$route["ajax/member/(ban|unban|search|delete-cmt)/*([0-9]*)"] = "admin/ajax/member";

/////////// ADMIN -> MANGA

$route["admin/manga/(list|add|pin)"]    = "admin/manga/page";
$route["admin/manga/([0-9]+)"]          = "admin/manga/detail";
$route["admin/manga/chapters/([0-9]+)"] = "admin/manga/chapters";

$route["ajax/manga/(unpin|pin|add-manga|upload-img|drop-manga|update-all-chapters|update|add-tag|remove-tag|change-cover-img|add-author|remove-author)/*([0-9]*)"] = "admin/ajax/manga";

/////////// ADMIN -> CHAPTER

$route["admin/chapter/(detail|add)/([0-9]+)"] = "admin/chapter/page";
$route["ajax/chapter/(update|delete|add|add-by-url)/([0-9]+)"] = "admin/ajax/chapter";

/////////// ADMIN -> TAG

$route["admin/tag/(list|detail)/*([0-9]*)"]      = "admin/tag/page";
$route["ajax/tag/(add-tag|delete-tag|update-tag)/([0-9]+)"] = "admin/ajax/tag";

/////////// ADMIN -> REPORT

$route["admin/report/(detail|list)/*([0-9]*)"] = "admin/report/page";
$route["ajax/report/(delete)/([0-9]+)"]        = "admin/ajax/report";

/////////// ADMIN -> REPORT

$route["admin/crawl"] = "admin/crawl/page";
$route["ajax/crawl/(add-manga|stop)"]  = "admin/ajax/crawl";

/////////// ADMIN -> REPORT

$route["admin/image"]          = "admin/image/page";
$route["ajax/image/(upload|delete)"]  = "admin/ajax/image";

/////////// ADMIN -> ORTHERS

$route["admin/others"] = "admin/others/page";
$route["ajax/(set-tag|del-tag|clean-cache)"] = "admin/ajax/others";

new application($route);

?>