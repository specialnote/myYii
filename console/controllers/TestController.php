<?php
namespace console\controllers;

use yii\console\Controller;

class TestController extends Controller{
    public function actionRun(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1',6379);
        echo '============= START ============='.PHP_EOL;
        var_dump(\Resque::pop('article_spider'));
        echo '============= END ============='.PHP_EOL;
    }
}
