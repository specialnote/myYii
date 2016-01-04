<?php
namespace frontend\modules\account;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->defaultRoute = 'user';
    }
}
?>