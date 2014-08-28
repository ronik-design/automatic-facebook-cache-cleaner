<div class="afu_linter_post_control">

    <?php
    include_once('nicejson.php');
    $afu_linter_tolint_url = get_permalink($post->ID);

    $access_token="283384575022710|7b2ccb27f513a97f7e278601bf2197d9"; //replace with your app details
    $params = array("id"=> $afu_linter_tolint_url,"scrape"=>"true","access_token"=>$access_token);
    $ch = curl_init("https://graph.facebook.com");
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_POST=>true,
        CURLOPT_POSTFIELDS=>$params
    ));
    $result = curl_exec($ch);
    ?>
    <pre>
    <?php

    // strip off optional Unicode BOM:
    if (substr($result, 0, 3) == "\xEF\xBB\xBF") {
        $result = substr($result, 3);
    }
    echo htmlspecialchars(json_format($result));
    ?>
    </pre>
    <br/>
    FB's cache for this post/page is automatically flushed whenever you save it, so your shares will always be up to date.<br/>
    <b>Test <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?=$afu_linter_tolint_url?>">sharing this on Facebook</a></b> | Check <a href="http://developers.facebook.com/tools/debug/og/object?q=<?=$afu_linter_tolint_url?>" target="_blank">FB's debug page for this URL</a>

</div>