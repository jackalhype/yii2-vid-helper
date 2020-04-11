<?php
namespace backend\assets;

use yii\web\AssetBundle;

class DatatablesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/datatables/dataTables.bootstrap.css',
        'css/datatables/dataTables.responsive.css'
    ];
    public $js = [
        'js/datatables/jquery.dataTables.min.js',
        'js/datatables/dataTables.bootstrap.min.js',
        'js/datatables/dataTables.responsive.js',
    ];
}