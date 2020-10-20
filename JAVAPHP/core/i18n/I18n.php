<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
 function I18n($model){
     //I18N文件路径
    $i18nFile = APP_I18N_PATH."/lang_".APP_I18N_NAME.".properties";
    $file = file($i18nFile);
    //foreach($file as &$line) echo "aaaaaa:".$line.'<br />';
    //include($i18nFile);
    //echo "ModelAndView:".${label.index.name};
    foreach($file as &$line){
        $arr = preg_split("/=/",preg_replace("/\$/","",preg_replace("/\{/","",preg_replace("/\}/","",trim($line)))));
        if(count($arr)==2){
              $model->put($arr[0],$arr[1]);
        }
    }
    return $model;
 }
?>