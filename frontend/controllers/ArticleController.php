<?php
    namespace frontend\controllers;

    use common\models\Article;
    use yii\data\Pagination;

    class FrontendController extends BaseController{
        public function actionIndex(){
            $data = Article::find()->where(['status'=>Article::STATUS_DISPLAY])->orderBy(['created_at'=>SORT_DESC]);
            $pages = new Pagination(['totalCount'=>$data->count(),'pageSize'=>20]);

            //全部文章
            $articles = $data->offset($pages->offset)->limit($pages->limit)->all();
            //推荐文章
            $recommendArticles = Article::getRecommendArticle();

            return $this->render('index',[
               'articles'=>$articles,
                'recommendArticles'=>$recommendArticles,
            ]);
        }
    }
?>