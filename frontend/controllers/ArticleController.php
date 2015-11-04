<?php
    namespace frontend\controllers;

    use common\models\Article;
    use common\models\Category;
    use common\models\Tag;
    use yii\data\Pagination;
    use yii\web\NotFoundHttpException;
    use Yii;

    class ArticleController extends BaseController{
        public $layout = 'mini';

        public function actionIndex(){
            $data = Article::find()->where(['status'=>Article::STATUS_DISPLAY])->orderBy(['publish_at'=>SORT_DESC]);
            $pages = new Pagination(['totalCount'=>$data->count(),'pageSize'=>20]);

            //全部文章
            $articles = $data->offset($pages->offset)->limit($pages->limit)->all();
            //推荐文章
            $recommendArticles = Article::getRecommendArticle();
            //分类
            $categories = Category::find()->where(['status'=>Category::STATUS_DISPLAY])->all();
            //标签
            $tags = Tag::find()->orderBy(['article_count'=>SORT_DESC,'id'=>SORT_DESC])->limit(10)->all();
            return $this->render('index',[
               'articles'=>$articles,
                'recommendArticles'=>$recommendArticles,
                'categories'=>$categories,
                'tags'=>$tags
            ]);
        }

        public function actionDetail($id){
            if(!$article = Article::findOne($id)) throw new NotFoundHttpException('ID 为 '.$id.' 的文章没有找到');

            //文章标签
            $tags = $article->getArticleTag();
            //相关文章


            return $this->render('detail',[
                'article'=>$article,
                'tags'=>$tags
            ]);
        }
    }
?>