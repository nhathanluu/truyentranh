<?php
    class html{

static $auto_ads = <<<HTML
<ins class="adsbygoogle gg-ads" 
style="display:block" 
data-ad-client="ca-pub-9352384461873258" 
data-ad-slot="1529850732" 
data-ad-format="auto" 
data-full-width-responsive="true">
</ins>
HTML;

static $border = <<<HTML
<div class="border"><div class="hearts"><img src="/public/icons/small-devilchan.png" class="center" alt="border"></div></div>
HTML;

        static function pagi($pagi,$target = "_self"){

            $ar      = svg::$arrow_right;
            $cur_url = $_SERVER['REQUEST_URI'];

            $hidden = "";

            foreach($_GET as $name => $value) {
                $name = htmlspecialchars($name);
                $value = htmlspecialchars($value);
                $hidden .= '<input type="hidden" name="'. $name .'" value="'. $value .'">';
            }

return <<<HTML
<div class="pagination-box">
<div class="pagination">
<ul class="clearfix">{$pagi}</ul>
<div class="jump">
<div class="jump-wrapper">
<form method="GET" target="{$target}" action="{$cur_url}">
<input type="text" name="page" placeholder="Jump" id="jump-input" aria-label="Jump">
{$hidden}
<button class="jump-button" title="Jump">
    <span class="jump-ok">{$ar}</span>
</button>
</form>    
</div>
</div>
</div>
</div>
HTML;
        }

        static function pagi_render($pagi){

            $html = "";

            $url = $_SERVER['REQUEST_URI'] .  ( @$_GET ? '&page=' : '?page=' );

            foreach($pagi as $k => $v) {
                $$k = $v;
            }

            if($button["prev"]){

$html .= <<<HTML
<li class="button"><a class="__link" href="{$url}{$button['prev']}" title="Prev page">&lt;</a></li>
HTML;

            }else{

$html .= <<<HTML
<li class="button disable"><a href="#" title="Prev page">&lt;</a></li>
HTML;
            }

            foreach ($pages as $page){
               
                $href = $url . $page["page"];
                $act  = @$page["act"] ? ' class="act" ' : "";
                $num  = $page["page"];

$html .= <<<HTML
<li{$act}><a class="__link" href="{$href}" title="Page {$num}">{$num}</a></li>
HTML;
            }

            if($button["next"]){
                
$html .= <<<HTML
<li class="button"><a class="__link" href="{$url}{$button['next']}" title="Next page">&gt;</a></li>
HTML;

            }else{

$html .= <<<HTML
<li class="button disable"><a href="#" title="Next page">&gt;</a></li>
HTML;
            }

            return $html;
        }

        static function pagi_render2($pagi){

            $html = "";

            foreach($pagi as $k => $v) {
                $$k = $v;
            }

            if($button["prev"]){

$html .= <<<HTML
<li class="button"><a class="__link" data-page-num="{$button['prev']}" href="#" title="Prev page">&lt;</a></li>
HTML;

            }else{

$html .= <<<HTML
<li class="button disable"><a href="#" title="Prev page">&lt;</a></li>
HTML;
            }

            foreach ($pages as $page){

                $act  = @$page["act"] ? ' class="act" ' : "";
                $num  = $page["page"];

$html .= <<<HTML
<li{$act}><a class="__link" data-page-num="{$num}" href="#" title="Page {$num}">{$num}</a></li>
HTML;
            }

            if($button["next"]){
                
$html .= <<<HTML
<li class="button"><a class="__link" data-page-num="{$button['next']}" href="#" title="Next page">&gt;</a></li>
HTML;

            }else{

$html .= <<<HTML
<li class="button disable"><a href="#" title="Next page">&gt;</a></li>
HTML;
            }

            return $html;
        }

        static function breadcrumb($arr,$t = []){

            $len       = count($arr);
            $list_item = "";
            $start     = 2;
            $li        = "";
            $max       = max(0,$len -1);

            for ($i=0; $i < $len - 1; $i++){

                $name = $arr[$i]["name"];
                $item = $arr[$i]["item"];

                $list_item .= '{
                    "@type": "ListItem",
                    "position":' . $start . ',
                    "name" : "' . $name . '",
                    "item" : "' . trim(SITE_URL,"/") . $item . '"
                },';

                $li .= '<li><a title="' . $name . '" href="' . $item . '" class="__link fc1-v2">' .  $name . '</a></li>';

                $start++;
            }

            $namel = $arr[$max]["name"];

            $list_item .= '{
                "@type": "ListItem",
                "position":' . ($len + 1) . ',
                "name" : "' . $namel . '"
            }';

            $li .= "<li>" . $namel . "</li>";

            $schema = json_encode(json_decode('{
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                    "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "' . trim(SITE_URL,"/") . '"
                },' . $list_item . ']}'));

            if($t){

                $breadcrumb = '<ol class="breadcrumb"><li><a title="' . $t["title"] . '" href="' . $t["href"] . '" class="__link fc1-v2" >' . $t["name"] . '</a></li>' . $li . '</ol>';

            }else{

                $breadcrumb = '<ol class="breadcrumb"><li><a title="' . SITE_NAME . '" href="/" class="__link fc1-v2" >' . SITE_NAME .'</a></li>' . $li . '</ol>';
            }
            return '<script type="application/ld+json">' . $schema .'</script>' . $breadcrumb;
        }

        static function img_schema($images){
        
            if($images){

                $i = 1;
                $schema = "";
                foreach ($images as $img){

                    $img_id      = $img["img_id"];
                    $img_name    = $img["img_name"];
                    $img_preview = $img["img_preview"];
                    $img_full    = $img["img_full"];

                    $url = ORIGINAL . img_path($img_id) . $img_full;
                    $thumbnailUrl = SITE_URL . "preview/" . img_path($img_id) . $img_preview;

                    $schema.= '{
                        "@type": ["ListItem","ImageObject"],
                        "position":"' . $i . '",
                        "name":"' . $img_name .'",
                        "url":"' . $url . '",
                        "thumbnailUrl":"' . $thumbnailUrl . '"
                    },';
                    $i++;
                }

                $schema = trim($schema,",");

                $ss = '{
                  "@context": "http://schema.org",
                  "@type": "ItemList",
                  "itemListElement": [' . $schema . ']}';

                return $script_schema = '<script type="application/ld+json">' . json_encode(json_decode($ss)) . '</script>';
            }
        }
    }
?>