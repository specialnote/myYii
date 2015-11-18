<?php

namespace backend\controllers;


class ChartController extends BaseController
{
    public function actionIndex(){
        return $this->render('index');
    }
}