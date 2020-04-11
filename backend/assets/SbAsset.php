<?php
namespace backend\assets;

use yii\web\AssetBundle;

class SbAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sb-admin-2.css',
        'css/metisMenu.min.css',
        'js/jquery-ui-1.12.1/jquery-ui.min.css',
        'js/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css',
    ];
    public $js = [
        'js/sb-admin-2.js',
        'js/metisMenu.min.js',
        'js/jquery-ui-1.12.1/jquery-ui.min.js',
        'js/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js',
        'js/main.js',
    ];
}
