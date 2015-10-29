<?php
    namespace console\models;

    use common\models\Article;
    use common\models\Gather;

    class ArticleSpider{
        protected $category = [];//网站文章分类
        protected $baseUrl = '';//网站域名
        protected $name = '';//网站名称

        /**
         * 判断文章是否采集
         * @param $url
         * @return bool
         */
        protected function isGathered($url){
            $gather = Gather::find()->where(['url'=>md5(trim($url)),'res'=>true])->one();
            return $gather?true:false;
        }

        /**
         * 插入URL队列
         * @param $category
         * @param $url
         * @param $className
         * @param string $publishTime
         */
        public function enqueue($category,$url,$className,$publishTime=''){
            \Resque::enqueue('article_spider', 'console\models\ArticleJob',['category'=>$category,'url'=>$url,'className'=>$className,'publishTime'=>$publishTime]);
        }

        /**
         * 将文章插入数据库
         * @param $title
         * @param $content
         * @param $publish_at
         * @return bool
         */
        public static function insert($title,$content,$publish_at){
            $article = new Article();
            $article->title = $title;
            $article->content = $content;
            $article->author = 'yang';
            $article->status = Article::STATUS_HIDDEN;
            $article->publish_at = $publish_at;
            $res = $article->save(false);
            return $res?true:false;
        }

        /**
         * 采集日志
         * @param $url
         * @param $category
         * @param $res
         * @param $result
         */
        public function addLog($url,$category,$res,$result){
            $gather = new Gather();
            $gather->name = $this->name;
            $gather->category = $category;
            $gather->url = md5($url);
            $gather->url_org = $url;
            $gather->res = $res;
            $gather->result = $result;
            $gather->save();
        }
    }
?>