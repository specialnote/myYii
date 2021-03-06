<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'controllerMap'=>[
        'mArticle'=>'frontend\controllers\MyArticleController',
    ],//强制控制器ID和类名对应
    'defaultRoute' => 'site',//修改默认控制器
    //'defaultRoute' => 'article',
    'modules'=>[
        'account'=>'frontend\modules\account\Module'
    ],//在应用中使用模块
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules'=>[
                '<controller:(article)>/<action:(detail)>/<id:\d+>'=>'<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
