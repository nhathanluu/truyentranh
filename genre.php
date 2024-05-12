<?php
    require_once "init.php";


    if(isset($this)){

        $model = $this;

    }else{

        $model = new model();
        $model->pg_connect();

    }

    $rs = $model->pg_select_obj(
        "tag_name",
        "tags
            ORDER BY tag_name"
    );

    $count = count($rs);

    function ________t($start,$max,$tags){

        $html = "";

        for ($i = $start; $i < $max ; $i++){

            $tag_name = ucname($tags[$i]["tag_name"]);

            $html .= '<a href="/the-loai/' . urlencode($tag_name) . '" title="' . replace_quot($tag_name) . '">' . $tag_name . '</a>';
        }

        return $html;
    }

    function ________z($start,$max,$tags){

        $html = "";

        for ($i = $start; $i < $max ; $i++){

            $tag_name = ucname($tags[$i]["tag_name"]);

            $html .= '<li<?= " " . @$__' . clean123($tags[$i]["tag_name"]) . '?>><a href="/the-loai/' . urlencode($tag_name) . '" title="' . replace_quot($tag_name) . '">' . $tag_name . '</a></li>';
        }

        return $html;
    }

    $html  = "";
    $html2 = "";
    $html3 = "";

    foreach ($rs as $tag){

        $tag_name = $tag["tag_name"];
        $url      = "/the-loai/" . urlencode($tag["tag_name"]);

        $html3 .= '<option <?= @$_' . clean123($tag_name) . ' ?> value="' . $url . '">' . ucname($tag_name) .'</option>';
    }

    if ($count % 2 == 0) {

        $el_per_col = $count / 4;

        for ($i = 0; $i < $count; $i += $el_per_col){ 

            $html .= '<div class="col-25">' . ________t($i,$i + $el_per_col,$rs) . '</div>';
        }

        ////////////////////////////////////////////////////////////////////////

        $el_per_col2 = ceil($count / 2);

        for ($i = 0; $i < $count; $i += $el_per_col2){ 
            
            $html2 .= '<ul class="clearfix">' . ________z($i,$i + $el_per_col2,$rs) . '</ul>';
        }

    }else{

        $el_per_col = ceil($count / 4);

        for ($i = 0; $i < $count; $i += $el_per_col){ 

            $html .= '<div class="col-25">' . ________t($i,min($i + $el_per_col,$count),$rs) . '</div>';
        }

        ////////////////////////////////////////////////////////////////////////

        $el_per_col2 = ceil($count / 2);

        for ($i = 0; $i < $count; $i += $el_per_col2){ 
            
            $html2 .= '<ul>' . ________z($i,min($i + $el_per_col2,$count),$rs) . '</ul>';
        }
    }

    if($html){

        file_put_contents(VIEW . "main/genre/tags_1.phtml",$html);
    }

    if($html2){

        file_put_contents(VIEW . "main/genre/tags_2.phtml",$html2);
    }

    if($html3){

        file_put_contents(VIEW . "main/genre/tags_3.phtml",$html3);
    }

?>