<?php
    namespace console\models;

    use yii\base\Exception;

    class ArticleJob{
        public function perform()
        {
            //获取队列内容属性
            $args=$this->args;
            $category= $args['category'];
            $url= $args['url'];
            $baseClassName= $args['className'];
            $publishTime = $args['publishTime'];

            $className = '\console\models\\'.ucfirst(strtolower($baseClassName)).'Spider';
            if(!class_exists($className)){
                throw new Exception($baseClassName.' Class does not exist');
            }
            $class = new $className;
            $res = $class->getContent(trim($url),$category);
            $res = json_decode($res,true);
            if($res){
                $title = $res['title'];
                $content = $res['content'];
                $time = $res['time'];
                $time = $publishTime?:$time;
                $result = $class->insert($title,$content,$time);
                $class->addLog($url,$category,$result,$title);
            }

        }
    }