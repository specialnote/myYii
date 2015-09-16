<?php

namespace backend\assets;

use yii\web\AssetBundle;


class UEditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'ueditor/ueditor.config.js',
        'ueditor/ueditor.all.min.js',
        'ueditor/lang/zh-cn/zh-cn.js',
    ];
    public $depends = [

    ];
}
