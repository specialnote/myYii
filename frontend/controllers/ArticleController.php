<?php
    namespace frontend\controllers;

    use common\models\Article;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;

    class ArticleController extends BaseController{
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

        public function actionDetail($id){
            if(!$article = Article::findOne($id)) throw new NotFoundHttpException('ID 为 '.$id.' 的文章没有找到');

            //文章标签
            $tags = $article->getArticleTag();

            var_dump($tags);
        }
    }
?>