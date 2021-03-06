<?php
return [
    'language'=>'zh-CN',
    'timeZone'=>'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'formatter' => [
            'nullDisplay'=>'-',
            'locale'=>'zh-CN',
            'dateFormat' => 'yyyy-MM-dd',
            'timeFormat' => 'HH:mm',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'decimalSeparator' => '.',
            'thousandSeparator' => ',',
            'currencyCode' => '&yen;',
        ],
        'session' => [
            'class' => 'yii\web\Session',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ],
    ],
];
