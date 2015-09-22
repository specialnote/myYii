<?php
namespace backend\controllers;

use common\models\NodeForm;

class NodeController extends BaseController{
    public function actionIndex(){
        $authManager = \Yii::$app->authManager;
        $nodes = $authManager->getPermissions();

      //  var_dump($nodes);die;
        return $this->render('index',[
            'nodes'=>$nodes,
        ]);
    }

    public function actionCreate(){
        $model = new NodeForm();

        if($model->load(\Yii::$app->request->post()) && $model->save()){

            \Yii::$app->session->setFlash('success','节点['.$model->name.']添加成功');
            return $this->redirect(['/node/index']);
        }else{
            return $this->render('create',[
                'model'=>$model,
            ]);
        }
    }


}