<?php
namespace console\controllers;

use yii;

class QueueController extends yii\console\Controller{
    /*
     * cd /www/test/advanced/myYii      进入项目目录
     * ps aux|grep yii      查看是否在后台运行  php yii queue/run
     *如果没有后台运行
     * QUEUE=* php yii queue/run &
     * */
    public function actionRun(){
        $QUEUE = getenv('QUEUE');
        if(empty($QUEUE)) {
            die("Set QUEUE env var containing the list of queues to work.\n");
        }

        $REDIS_BACKEND = getenv('REDIS_BACKEND');
        if(!empty($REDIS_BACKEND)) {
            \Resque::setBackend($REDIS_BACKEND);
        }

        $logLevel = 0;
        $LOGGING = getenv('LOGGING');
        $VERBOSE = getenv('VERBOSE');
        $VVERBOSE = getenv('VVERBOSE');
        if(!empty($LOGGING) || !empty($VERBOSE)) {
            $logLevel = \Resque_Worker::LOG_NORMAL;
        }
        else if(!empty($VVERBOSE)) {
            $logLevel = \Resque_Worker::LOG_VERBOSE;
        }

        $APP_INCLUDE = getenv('APP_INCLUDE');
        if($APP_INCLUDE) {
            if(!file_exists($APP_INCLUDE)) {
                die('APP_INCLUDE ('.$APP_INCLUDE.") does not exist.\n");
            }

            require_once $APP_INCLUDE;
        }

        $interval = 5;
        $INTERVAL = getenv('INTERVAL');
        if(!empty($INTERVAL)) {
            $interval = $INTERVAL;
        }

        $count = 1;
        $COUNT = getenv('COUNT');
        if(!empty($COUNT) && $COUNT > 1) {
            $count = $COUNT;
        }

        if($count > 1) {
            for($i = 0; $i < $count; ++$i) {
                $pid = pcntl_fork();
                if($pid == -1) {
                    die("Could not fork worker ".$i."\n");
                }
                // Child, start the worker
                else if(!$pid) {
                    $queues = explode(',', $QUEUE);
                    $worker = new \Resque_Worker($queues);
                    $worker->logLevel = $logLevel;
                    fwrite(STDOUT, '*** Starting worker '.$worker."\n");
                    $worker->work($interval);
                    break;
                }
            }
        }
        // Start a single worker
        else {
            $queues = explode(',', $QUEUE);
            $worker = new \Resque_Worker($queues);
            $worker->logLevel = $logLevel;

            $PIDFILE = getenv('PIDFILE');
            if ($PIDFILE) {
                file_put_contents($PIDFILE, getmypid()) or
                die('Could not write PID information to ' . $PIDFILE);
            }

            fwrite(STDOUT, '*** Starting worker '.$worker."\n");
            $worker->work($interval);
        }
    }
}