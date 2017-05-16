<?php
$opt = $_POST['opt'];

if ($opt == 1){
    $url = $_POST['url'];
    if (@fopen($url, "r")){
        $html = file_get_contents($url);
        preg_match('/charset\=[\"\'][^\"\']+[\"\']/i',$html,$charset);
        if ($charset){
            $charset = preg_replace('/^charset\=[\"\']|[\"\']$/','',$charset[0]);
            if ($charset AND $charset != 'UTF-8' AND $charset != 'utf-8')
                $html = iconv($charset, "UTF-8", $html);
        }
        $imgs_display = array();
        $desc_display = '';
        $title_display = '';
        preg_match_all('/<img[^\>]+src=[\"\'\>][^\"\'\>]+[\"\'\>]/ui',$html,$imgs); $imgs = $imgs[0];
        for ($i=0; $i<count($imgs); $i++){
            if (count($imgs_display) >= 16) break;
            preg_match('/src=\S+/u',$imgs[$i],$img_src);
            $img_src = preg_replace('/^src=[\"\'\>]|[\"\'\>]$/ui','',$img_src[0]);
            preg_match('/^https?\:\/\//ui',$url,$https);
            $url_base = preg_replace('/^https?\:\/\//ui','',$url);
            if (preg_match('/^\/[^\/]/',$img_src)){
                $url_base = explode('/',$url_base);
                $url_base = $url_base[0];
                if ($https[0]) $url_base = $https[0].$url_base;
            } else if (preg_match('/^(https?:\/|\/\/)/',$img_src)) $url_base = '';
            $img_src = $url_base.$img_src;
            $fh = fopen($img_src, "r");
            while(($str = fread($fh, 1024)) != null) $fsize += strlen($str);
            if ($fsize > 50000) array_push($imgs_display,$img_src);
        }
        preg_match('/\<title\>[^\<]+\<\/title\>/ui',$html,$title);
        if ($title[0]){
            if ($title[0] != '') $title_display .= preg_replace('/^\<title\>|\<\/title\>$/ui','',$title[0]);
        }
        preg_match('/\<meta[^\>]+name\=[\"\']description[\"\'][^\>]*/ui',$html,$text);
        if ($text[0]){
            preg_match('/content\=[\"\'][^\"\']*[\"\']/',$text[0],$text);
            if ($text[0] != '') $desc_display .= preg_replace('/^content=[\"\']|[\"\']$/ui','',$text[0]);
        }
        echo json_encode(array('title' => $title_display, 'desc' => $desc_display, 'imgs' => $imgs_display));
    } else echo "Page doesn't exists!";
} else if ($opt == 2){
    require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $img = $_POST['img'];

    $post_id = wp_insert_post(array(
        'post_title'    => wp_strip_all_tags($title),
        'post_content'  => "<img src='".$img."' />".$desc,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_category' => array(29)
    ));

    if ($post_id) echo "Article is successfully created!";
    else echo "Error! Article isn't created!";
}
?>