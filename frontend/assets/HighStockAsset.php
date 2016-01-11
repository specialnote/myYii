<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class HighStockAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/highstock.js',
        'js/myStock.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
