<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * jquery实现拖拽效果 jQuery Gridly
 */
class DateTimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/datepicker.min.css',
    ];
    public $js = [
        'js/datepicker.min.js',
        'js/datepicker.zh-cn.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
