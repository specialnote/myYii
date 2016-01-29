<?php
    namespace frontend\controllers;

    use Yii;
    use yii\web\Response;
    use common\models\FundNum;
    use yii\helpers\ArrayHelper;
    use common\models\FundData;
    use common\models\FundFilter;

    class ApiController extends BaseController{
        /**
         * 获取根据基金筛选条件进行筛选的结果
         * @return array|string
         */
        public function actionFundFilter(){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->isPost){
                $type = Yii::$app->request->post('type');
                if(!$type) return '';
                $num = [];
                foreach($type as $v){
                    $data = FundFilter::find()->select('fund_num')->distinct()->where(['type'=>$v])->all();
                    $n = ArrayHelper::getColumn($data,'fund_num');
                    $num = array_merge($n,$num);
                }
                $res = array_count_values($num);
                $array = [];
                foreach($res as $num=>$v){
                    if($v===count($type))$array[] = $num;
                }
                return $array;
            }
            return '';
        }

        /**
         * 获取若干指定基金的指定类型详情
         * @param $date_type
         * @return array|string
         */
        public function actionFundDetailFilter($date_type){
            if(!$date_type || !in_array($date_type,['month','week','day']))return '';
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $nums = Yii::$app->request->post('nums');
                if(!$nums)return '';
                $data = [];
                foreach($nums as $num){
                    $fund = FundNum::find()->where(['fund_num'=>$num])->one();
                    if(!$fund) continue;
                    $connection = Yii::$app->db;
                    if($date_type == 'month'){
                        $sql = "SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC";
                    }elseif($date_type == 'week'){
                        $sql = "SELECT YEAR(`date`) as `year`,MONTH(`date`) as `month`,WEEK(`date`) as `week`,SUM((rate+0)) AS sum_rate FROM fund_history WHERE fund_num = '".$num."' GROUP BY YEAR(`date`),MONTH(`date`),WEEK(`date`) ORDER BY YEAR(`date`) DESC,MONTH(`date`) DESC,WEEK(`date`) DESC ";
                    }elseif($date_type == 'day'){
                        $sql = "SELECT `date`,(rate+0) as sum_rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` DESC";
                    }else{
                        return '';
                    }
                    $command = $connection->createCommand($sql);
                    $posts = $command->queryAll();
                    $rate_data = ArrayHelper::getColumn($posts,'sum_rate');
                    $sd = 0;
                    if($rate_data){
                        $average = array_sum($rate_data)/count($rate_data);
                        $n = 0;
                        foreach($rate_data as $v){
                            $n += ($v - $average)*($v - $average);
                        }
                        $sd = sqrt($n/count($rate_data));
                    }else{
                        $average = 0;
                    }
                    $data[] = ['fund_num'=>$num,'type'=>FundNum::getTypeName($num),'average'=>number_format($average,2),'sd'=>number_format($sd,2)];
                }
                return $data;
            }else{
                return '';
            }
        }

        /**
         * 获取每个基金每天的盈利数据
         * @param $num
         * @return string
         */
        public function actionRateDayDetailData($num){
            $connection = Yii::$app->db;
            $command = $connection->createCommand("SELECT `date`,(rate+0) as rate FROM fund_history WHERE fund_num = '".$num."' ORDER BY `date` ASC");
            $posts = $command->queryAll();
            $data = [];
            foreach($posts as $v){
                $data[] = [strtotime($v['date'])*1000,floatval($v['rate'])];
            }
            return json_encode($data);
        }

        /**
         * 获取每个基金每天的净值数据
         * @param $num
         * @return string
         */
        public function actionPriceDayDetailData($num){
            $posts = FundData::find()->where(['fund_num'=>$num])->orderBy(['date'=>SORT_ASC])->all();
            $data = [];
            foreach($posts as $v){
                $data[] = [strtotime($v['date'])*1000,floatval($v['accnav'])];
            }
            return json_encode($data);
        }

        /**
         * 获取筛选编号中重复的数据
         * @return array
         */
        public function actionDuplicate(){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(\Yii::$app->request->isPost){
                $num = Yii::$app->request->post('num','');
                $array =  explode('##',rtrim($num,'##'));
                $num = str_replace('##','',trim($num,'##'));
                $num_array = explode('_',rtrim($num,'_'));
                if($num_array){
                    $count =  array_count_values ($num_array);
                    foreach($count as $key=>$v){
                        if($v !== count($array)){
                            unset($count[$key]);
                        }
                    }
                    return ['code'=>true,'msg'=>array_keys($count)];
                }
            }
            return ['code'=>false,'msg'=>''];
        }

        /**
         * ajax处理周筛选
         * @return array|string
         */
        public function actionGetWeekFilter(){
            $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>FundNum::TYPE_HH])->asArray()->all();
            $nums = ArrayHelper::getColumn($nums,'fund_num');
            $nums = '(\''.implode('\',\'',$nums).'\')';
            if(Yii::$app->request->isPost){
                $w = Yii::$app->request->post('w');
                if(count($w)>0){
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $posts = [];
                    foreach($w as $k=>$v){
                        if($k==5)break;
                        $connection = Yii::$app->db;
                        $command = $connection->createCommand("SELECT `fund_num`,WEEK(`date`) as `week`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND YEAR(`date`) =2016  AND WEEK(`date`)=".$v." GROUP BY fund_num,`week` ORDER BY rate DESC LIMIT 100");
                        $posts[$k] = $command->queryAll();
                    }
                    return $posts;
                }
            }
            return '';
        }

        /**
         * ajax处理每周重复数据
         * @return array|string
         */
        public function actionGetWeekDuplicate(){
            $nums = FundNum::find()->select('fund_num')->distinct()->where(['fund_type'=>FundNum::TYPE_HH])->asArray()->all();
            $nums = ArrayHelper::getColumn($nums,'fund_num');
            $nums = '(\''.implode('\',\'',$nums).'\')';
            if(Yii::$app->request->isPost){
                $w = Yii::$app->request->post('w');
                if(count($w)>0){
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $posts = [];
                    foreach($w as $k=>$v){
                        if($k==5)break;
                        $connection = Yii::$app->db;
                        $command = $connection->createCommand("SELECT `fund_num`,WEEK(`date`) as `week`,sum(rate+0) as rate FROM fund_history WHERE fund_num IN ".$nums." AND YEAR(`date`) = 2016 AND WEEK(`date`)=".$v." GROUP BY fund_num,`week` ORDER BY rate DESC LIMIT 100");
                        $posts =array_merge($posts,ArrayHelper::getColumn($command->queryAll(),'fund_num'));
                    }
                    $count =  array_count_values ($posts);
                    foreach($count as $key=>$v){
                        if($v !== count($w)){
                            unset($count[$key]);
                        }
                    }
                    return array_keys($count);
                }
            }
            return '';
        }

    }
?>