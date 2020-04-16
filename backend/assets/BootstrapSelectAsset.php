<?php
namespace backend\assets;
use yii\web\AssetBundle;

class BootstrapSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-select/bootstrap-select.min.css',
    ];
    public $js = [
        'js/bootstrap-select/bootstrap-select.min.js',
    ];
}