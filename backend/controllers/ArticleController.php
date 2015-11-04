<?php

namespace backend\controllers;

use common\models\Category;
use Yii;
use common\models\Article;
use common\models\ArticleSearch;
use backend\controllers\BaseController;
use yii\console\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends BaseController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "",//图片访问路径前缀
                    "imagePathFormat" => "/uploads/ueditor/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->status = Article::STATUS_DISPLAY;
        if ($model->load(Yii::$app->request->post()) && $model->saveArticle()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $tag = $model->getArticleTagToString();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tag'  =>$tag,
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
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
     * 采集文章管理
     */
    public function actionGather(){
        $query = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('gather',[
            'dataProvider'=>$dataProvider,
        ]);
    }

    /**
     * 采集文章预览
     * @param $id
     * @return string
     */
    public function actionPreview($id){
        $article = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]])->andWhere(['id'=>$id])->one();
        return $this->render('preview',[
            'article'=>$article,
        ]);
    }

    /**
     * 批量发布文章
     * @return string|\yii\web\Response
     */
    public function actionPublish(){
        if(Yii::$app->request->isPost){
            $ids = Yii::$app->request->post('ids');
            $url = Yii::$app->request->post('url')?:'/article/gather';
            if($ids){
                $num = 0;
                foreach($ids as $id){
                    $article = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]])->andWhere(['id'=>$id])->one();
                    if(!$article) continue;
                    $article->status = Article::STATUS_DISPLAY;
                    if($article->save(false)){
                        $num++;
                    }
                }
                Yii::$app->session->setFlash('success','成功发布文章【'.$num.'】篇');
                return $this->redirect($url);
            }else{
                Yii::$app->session->setFlash('error','文章不能为空，请选择文章');
                return $this->redirect($url);
            }
        }else{
            $query = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            return $this->render('publish',[
                'dataProvider'=>$dataProvider,
            ]);
        }

    }

    /**
     * 批量更改文章分类
     * @return string|\yii\web\Response
     */
    public function actionCategory(){
        if(Yii::$app->request->isPost){
            $ids = Yii::$app->request->post('ids');
            $url = Yii::$app->request->post('url')?:'/article/gather';
            $category_id = Yii::$app->request->post('category_id');
            if($ids){
                $category = Category::find()->where(['status'=>Category::STATUS_DISPLAY,'id'=>$category_id])->one();
                if($category){
                    $num = 0;
                    foreach($ids as $id){
                        $article = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]])->andWhere(['id'=>$id])->one();
                        if(!$article) continue;
                        $article->category_id = $category_id;
                        if($article->save(false)){
                            $num++;
                        }
                    }
                    Yii::$app->session->setFlash('success','成功更改文章分类【'.$num.'】篇');
                    return $this->redirect($url);
                }else{
                    Yii::$app->session->setFlash('error','文章分类不能为空');
                    return $this->redirect($url);
                }
            }else{
                Yii::$app->session->setFlash('error','文章不能为空，请选择文章');
                return $this->redirect($url);
            }
        }else{
            $query = Article::find()->where(['in','status',[Article::STATUS_GATHER,Article::STATUS_DISPLAY]]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);


            return $this->render('category',[
                'dataProvider'=>$dataProvider,
            ]);
        }
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
