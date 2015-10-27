<?php
    namespace console\controllers;

    use common\models\Gather;
    use console\models\ArticleSpider;
    use yii\console\Controller;
    use yii\web\NotFoundHttpException;

    class QueueController extends Controller{
        public function actionRun(){
            $redis = new \Redis();
            $redis->connect('127.0.0.1',6379);

            while($urlRes = $redis->LPOP('articleUrls')){
               try{
                   $res_array = json_decode($urlRes,true);
                   if($res_array['url'] && $res_array['className']){
                       $className = '\console\models\\'.ucfirst(strtolower($res_array['className'])).'Spider';
                       if(!class_exists($className)){
                           throw new NotFoundHttpException($className.' Class not found');
                       }
                       $spider = new $className;
                       $content = $spider->getContent($res_array['url']);
                       $content_array = json_decode($content,true);
                       $time = $res_array['publishTime']?:$content_array['time'];
                       if($content_array['title']){
                           $res = ArticleSpider::insert($content_array['title'],$content_array['content'],$time);
                           if($res){
                               $gather = new Gather();
                               $gather->url = md5($res_array['url']);
                               $gather->$res_array['url'];
                               $gather->category = $res_array['category'];
                               $gather->res = true;
                               $gather->result = $content_array['title'];
                               $gather->save(false);
                           }
                       }
                   }
               }catch(\Exception $e){
                   echo $e->getMessage().PHP_EOL;
               }
            }
        }
    }