<?php
    namespace console\models;

    use Goutte\Client;

    class Php100Spider extends ArticleSpider{
        private $_url;

        /**
         * 构造方法，初始化采集网站属性
         */
        public function __construct(){
            $this->name = 'PHP100';
            $this->baseUrl = 'http://www.php100.com/';
            $this->category = [
               '编辑开发'=>'http://www.php100.com/html/it/biancheng/',
                '前段技术'=>'http://www.php100.com/html/it/qianduan/',
                '互联网新闻'=>'http://www.php100.com/html/it/hulianwang/',
               '程序员生活'=>'http://www.php100.com/html/it/chengxuyuan/',
               'PHP入门'=>'http://www.php100.com/html/php/rumen/',
               'PHP函数'=>'http://www.php100.com/html/php/hanshu/',
               'PHP框架与应用'=>'http://www.php100.com/html/php/lei/',
              '开发接口 '=>'http://www.php100.com/html/php/api/',
               'HTML '=>'http://www.php100.com/html/program/html/',
               'DIV、CSS '=>'http://www.php100.com/html/program/divcss/',
               'HTML5 '=>'http://www.php100.com/html/program/html5/',
               'XML '=>'http://www.php100.com/html/program/xml/',
               'Apache '=>'http://www.php100.com/html/program/apache/',
               'Nginx '=>'http://www.php100.com/html/program/nginx/',
               'Linux '=>'http://www.php100.com/html/program/linux/',
               'Mysql '=>'http://www.php100.com/html/program/mysql/',
               'Javascript '=>'http://www.php100.com/html/program/javascript/',
            ];
        }

        /**
         * 采集执行函数,调用 getPages ，获取所有分页 ；然后调用 urls ，获取每页文章的文章url，并将他们存入队列
         */
        public function process(){
            foreach($this->category as $category=>$url){
                $pages = $this->getPages($url,$category);
                if($pages){
                    foreach($pages as $p){
                        $this->urls($category,$p);
                    }
                }
            }

        }

        /**
         * 获取当前网站指定分类的分页
         * @return array
         */
        private function getPages($pageUrl,$category){
            $client = new Client();
            $crawler = $client->request('GET', $pageUrl);
            //获取分页
            $crawler->filter('.listsLeft .pages  a')->each(function ($node) use($pageUrl,$category) {
                if($node){
                   try{
                       $this->_url[] = $this->baseUrl.trim($node->attr('href'));
                   }catch(\Exception $e){
                       $this->addLog($pageUrl,$category,false,$e->getMessage());
                   }
                }
            });
            return array_unique($this->_url);
        }

        /**
         * 获取每页的文章分页
         * @param $category
         * @param $url
         */
        private function urls($category,$url){
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('.listsLeft ul li h2 a')->each(function ($node) use($category,$url) {
                if($node){
                   try{
                       $u = trim($node->attr('href'));
                       if(!$this->isGathered($u)){
                           $this->enqueue($category,$u,'php100');
                       }
                   }catch(\Exception $e){
                       $this->addLog($url,$category,false, $e->getMessage());
                   }
                }
            });
        }

        /**
         * 获取指定url的文章内容
         * @param $url
         * @param $category
         * @return string
         */
        public function getContent($url,$category){
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $node = $crawler->filter('#content .listsLeft')->eq(0);
            if($node){
                try{
                    $title = $node->filter('.newsContent h1');
                    $content = $node->filter('.newsContent');
                    if($title && $content){
                        $title = trim($title->text());
                        $content = $content->html();
                        $time = date('Y-m-d',time());
                        return json_encode(['title'=>$title,'content'=>$content,'time'=>$time]);
                    }
                }catch(\Exception $e){
                    $this->addLog($url,$category,false,$e->getMessage());
                }
            }
            return '';
        }
    }
