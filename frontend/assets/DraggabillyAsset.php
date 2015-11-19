<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * jquery实现拖拽效果 draggabilly.pkgd.js 在指定区域内部任意拖拽
 */
class DraggabillyAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/draggabilly.pkgd.js',
        'js/draggabilly.pkgd.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
