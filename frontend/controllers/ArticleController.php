<?php
    namespace frontend\controllers;

    use common\models\Article;
    use common\models\ArticleTag;
    use common\models\Category;
    use common\models\Tag;
    use yii\data\Pagination;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\web\NotFoundHttpException;
    use Yii;

    class ArticleController extends BaseController{
        public $layout = 'mini';

        /**
         * 获取文章列表（可以按分类、标签获取文章列表）
         * @return string
         */
        public function actionIndex(){
            $category_id = Html::encode(Yii::$app->request->get('category_id'));
            $tag_id = Html::encode(Yii::$app->request->get('tag_id'));
            $data = Article::find()->where(['status'=>Article::STATUS_DISPLAY,'category_id'=>$category_id]);
            if($tag_id){
                $article_tags = ArticleTag::find()->where(['tag_id'=>$tag_id])->all();
                $article_ids = ArrayHelper::getColumn($article_tags,'article_id');
                $data = $data->andWhere(['in','id',$article_ids]);
            }
            $pages = new Pagination(['totalCount'=>$data->count(),'pageSize'=>20]);

            //全部文章
            $articles = $data->orderBy(['publish_at'=>SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
            //推荐文章
            $recommendArticles = Article::getRecommendArticle();
            //分类
            $categories = Category::find()->where(['status'=>Category::STATUS_DISPLAY])->all();
            //热门标签
            $tags = Tag::find()->orderBy(['article_count'=>SORT_DESC,'id'=>SORT_DESC])->limit(10)->all();
            return $this->render('index',[
               'articles'=>$articles,
                'recommendArticles'=>$recommendArticles,
                'categories'=>$categories,
                'tags'=>$tags,
                'category_id'=>$category_id,
                'tag_id'=>$tag_id,
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