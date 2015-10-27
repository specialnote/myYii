<?php
    namespace console\models;

    use common\models\Article;

    class ArticleSpider{
        protected $category = [];//网站文章分类
        protected $baseUrl = '';//网站域名
        protected $name = '';//网站名称

        /**
         * 判断文章是否采集
         */
        protected function isGathered(){

        }

        /**
         * 插入数据库
         */
        public static function insert($title,$content,$publish_at){
            $article = new Article();
            $article->title = $title;
            $article->content = $content;
            $article->author = 'yang';
            $article->status = Article::STATUS_HIDDEN;
            $article->publish_at = $publish_at;
            $res = $article->save(false);
            return $res;
        }

        /**
         * 插入URL队列
         */
        protected function enqueue($name,$baseUrl,$category,$url,$className,$publishTime=''){
            $redis = new \Redis();
            $redis->connect('127.0.0.1',6379);
            $redis->LPUSH('articleUrls',json_encode(['name'=>$name,'baseUrl'=>$baseUrl,'category'=>$category,'url'=>$url,'className'=>$className,'publishTime'=>$publishTime]));
        }
    }
?>