<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

echo 'Last login time :'.Yii::$app->user->identity->last_login_time;
?>


