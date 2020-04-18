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

<style>
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