<?php

namespace backend\controllers;

use common\models\Fund;
use Goutte\Client;
use Yii;
use common\models\FundData;
use common\models\FundDataSearch;
use backend\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FundDataController implements the CRUD actions for FundData model.
 */
class FundDataController extends BaseController
{

    /**
     * Lists all FundData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FundDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport(){
        set_time_limit(-1);
        $fund = Fund::find()->select('num')->distinct()->all();
        $fund = ArrayHelper::getColumn($fund,'num');
        foreach($fund as $num){
            if($num){
                $url = 'http://fund.10jqka.com.cn/'.$num.'/historynet.html';
                $content = file_get_contents($url);
                if(strstr($content,'JsonData = [')){
                    $content =  substr($content,strpos($content,'JsonData = [')+11);
                    $content =  substr($content,0,strpos($content,'useData = JsonData;'));
                    $content = trim($content);
                    $content = trim($content,';');
                    if($content){
                        $data = json_decode($content,true);
                        if($data){
                            foreach($data as $v){
                                if($v['date']&&$v['net']&&$v['totalnet']&&$v['inc']&&$v['rate']){
                                    $fundData = FundData::find()->where(['date'=>$v['date'],'fund_num'=>$num])->one();
                                    if($fundData)continue;
                                     echo $num.'--'.$v['date'].'--'.$v['net'].'--'.$v['totalnet'].'--'.$v['inc'].'--',$v['rate'].'<br/>';
                                    $fundData = new FundData();
                                    $fundData->date = $v['date'];
                                    $fundData->fund_num = $num;
                                    $fundData->iopv = $v['net'];
                                    $fundData->accnav = $v['totalnet'];
                                    $fundData->growth = $v['inc'];
                                    $fundData->rate = $v['rate'];
                                    $fundData->save();
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    /**
     * Displays a single FundData model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FundData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FundData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FundData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FundData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FundData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FundData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FundData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
