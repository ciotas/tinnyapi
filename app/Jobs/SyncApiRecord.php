<?php

namespace App\Jobs;
use ERedis as Redis;
use Rabbitmq;

class SyncApiRecord
{
    protected $queue;
    protected $apirecord;

    /**
     * SyncApiRecord constructor.
     */
    public function __construct()
    {
        $this->queue = 'msgs';
        $this->apirecord = 'record_java_error_api_request_data_list';
    }

    /**
     * @param $message
     */
    public function getAPirecord($message){
        $amqp = new Rabbitmq($this->queue);
        $amqp->onPublish($message);
        $amqp->onClose();
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return Redis::lpop($this->apirecord);
    }
}


//实例化
$syncApiRecord = new SyncApiRecord();
//$redis = new Redis();
//获取数据
//while (true){
    $message = $syncApiRecord->getMsg();
    //进入队列
$message ='{"a":1,"b":2}';
    $syncApiRecord->getAPirecord($message);
//}
