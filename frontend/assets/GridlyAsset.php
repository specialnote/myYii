<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * jquery实现拖拽效果 jQuery Gridly
 */
class GridlyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery.gridly.css',
    ];
    public $js = [
        'js/jquery.gridly.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
