<?php

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath("jquery")."class.tx_jquery.php");

/* Ekstra hooks */
class tx_tendyoutubettnews_additionalMarkers extends tslib_pibase {
    public $prefixId = "tendyoutubettnews_hooks";

    function extraItemMarkerProcessor($markerArray, $row, $lConf, &$obj) {

        if ($row['tx_tendyoutubettnews_youtube_video'] == 0) {
            $markerArray['###tend_youtube_videos###'] = '';
        } else {
            /* Processing right row... */
            tx_jquery::includeLib();
            
            /* Default CSS */
            $tmp_css = file_get_contents(t3lib_extMgm::siteRelPath("tend_youtube")."res/css/tend_youtube.css");
            $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId."_css"]
                    = TSpagegen::inline2TempFile($tmp_css, 'css');
            $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId."_pp_css"] =
                    '<link href="typo3conf/ext/tend_youtube/src/prettyPhoto/css/prettyPhoto.css" type="text/css" rel="stylesheet""></link>';

            /* For prettyPhoto JS*/
            $tmp_css = file_get_contents(t3lib_extMgm::siteRelPath("tend_youtube")."src/prettyPhoto/js/jquery.prettyPhoto.js");
            $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId."_pp_js"]
                    = TSpagegen::inline2TempFile($tmp_css, 'js');

            /* JS */
            $js = '
            $(document).ready(function(){
                $(".video_ph a[rel^=\'prettyPhoto\']").prettyPhoto({theme:\'light_square\'});
            });
            ';

            $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId."_pp_js_activate"] = TSpagegen::inline2TempFile($js, 'js');

            /* Templates itd ... */
            $this->templateObrazec = $obj->cObj->fileResource("EXT:tend_youtube/res/templates/tend_youtube_list.html");

            $template["total"] = $obj->cObj->getSubpart($this->templateObrazec,'###template###');
            $template["video"] = $obj->cObj->getSubpart($template["total"],'###video###');
           
            $content = $obj->cObj->substituteMarkerArray($template["total"], array(
                    "###form_name###"=>$this->prefixId,
                    "###pid###"=>$obj->pid,
                    "###pager###" => "", 
                    ));

            $yt = new tx_tendyoutube();
            $ids = $row['tx_tendyoutubettnews_youtube_video'];
            $video_database = $yt->getRows("SELECT
                uid,title,keywords,description,
                youtube_user, youtube_id
                FROM tx_tendyoutube_video WHERE
                deleted=0 AND
                hidden=0 AND
                is_error=0 AND
                uid in (".$ids.")
                ");

            $videos_tmp = "";

            //if($video_database)
            foreach($video_database as $video){
                $chr_tmp = str_replace('Š','&#352;',$video["title"]);
                $videos_tmp .= $obj->cObj->substituteMarkerArray($template["video"],
                    array(
                    "###title###"=> $chr_tmp,
                    "###description###"=> $video["description"],
                    "###description_short###"=> $video["description"],
                    "###category###"=> "&nbsp;", // $video->getVideoCategory(),
                    //"###tags###"=>$video->getVideoTags(),
                    "###image_url###"=> sprintf("http://img.youtube.com/vi/%s/default.jpg",$video["youtube_id"]),
                    "###image_width###"=>120,
                    "###image_height###"=>90,
                    "###video_url###"=> sprintf("http://www.youtube.com/watch?v=%s",$video["youtube_id"]))
                );
            }//endforeach

            $content = $obj->cObj->substituteSubpart($content, '###video_list###', $videos_tmp);
           
            /* Marker */
            $markerArray['###tend_youtube_videos###'] = $content;
        }

        return $markerArray;
    }
}
