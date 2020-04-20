<?php
$this->registerJsFile('@web/js/main/video.js', ['depends' => 'frontend\assets\AppAsset']);

/*
@var $video_node
@var $video_html
*/
?>

<div class="video-wrap">
    <?= $video_html ?>
</div>

<div class="tip" id="tip"></div>

<style>
    .tip {
        position: fixed;
        top: 0;
        right: 0;
        min-width: 30px;
        max-width: 60px;
        height: 35px;
        background-color: #050509;
        color: #e83232;
        padding: 6px;
        font-size: 2.2em;
    }

    .video-wrap {
        font-size: 1.2em;
    }

    .lvl-0,.lvl-1,.lvl-2,.lvl-3,.lvl-4,.lvl-5,.lvl-6 {
        margin-top: 0.25em;
    }
    .lvl-0 {
        font-size: 1.5em;
    }
    .lvl-1 {
        font-size: 0.87em;
        margin-top: 0.35em;
    }
    .lvl-2 {
        font-size: 0.80em;
    }
    .lvl-3 {
        font-size: 0.90em;
    }
    .lvl-4 {
        font-size: 0.90em;
    }
    .lvl-5 {
        font-size: 0.92em;
    }

    .hide {
        display:none;
    }
</style>