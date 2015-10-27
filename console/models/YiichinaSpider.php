<?php
    namespace console\models;

    use Goutte\Client;

    class YiichinaSpider extends ArticleSpider{
        private $_url;
        public function __construct(){
            $this->name = 'Yiichina';
            $this->baseUrl = 'http://www.yiichina.com';
            $this->category = [
                '教程'=>'http://www.yiichina.com/tutorial',
                '扩展'=>'http://www.yiichina.com/extension',
                '源码'=>'http://www.yiichina.com/code',
            ];
        }

        public function process(){
            foreach($this->category as $c=>$url){
                $pages = $this->getPages($url);
                if($pages){
                    foreach($pages as $p){
                        $this->getUrls($c,$p);
                    }
                }
            }
        }

        /**
         * 获取当前网站分类的分页
         * @return array
         */
        private function getPages($pageUrl){
            $client = new Client();
            $crawler = $client->request('GET', $pageUrl);
            //获取分页
            $crawler->filter('.media-list .pagination li a')->each(function ($node) {
                if($node){
                   try{
                       $this->_url[] = $this->baseUrl.trim($node->attr('href'));
                   }catch(\Exception $e){
                       echo $e->getMessage().PHP_EOL;
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
        private function getUrls($category,$url){
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $crawler->filter('.media-list .media')->each(function ($node) use($category) {
                if($node){
                   try{
                       $a = $node->filter('.media-body .media-heading a');
                       if($a){
                           $url = $this->baseUrl.trim($a->attr('href'));
                           $this->enqueue($this->name,$this->baseUrl,$category,$url,'yiichina');
                       }
                   }catch(\Exception $e){
                       echo $e->getMessage().PHP_EOL;
                   }
                }
            });
        }

        public function getContent($url){
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $node = $crawler->filter('.col-lg-9')->eq(0);
            if($node){
                try{
                    $title = $node->filter('.page-header h1');
                    $time = $node->filter('.action .time');
                    if($title && $time){
                        $title = trim($title->text());
                        $content = $node->html();
                        $time = $time->text();
                        return json_encode(['title'=>$title,'content'=>$content,'time'=>$time]);
                    }
                }catch(\Exception $e){
                    echo $e->getMessage().PHP_EOL;
                }
            }
            return '';
        }
    }
