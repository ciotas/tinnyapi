<?php

namespace App\Jobs;
use App\Repositories\RecordapiRepository;
use Rabbitmq;

class SyncApiRecord
{
    protected $queue;
    protected $apirecord;
    protected $record;

    /**
     * SyncApiRecord constructor.
     */
    public function __construct()
    {
        $this->queue = 'msgs';
        $this->apirecord = 'record_java_error_api_request_data_list';
        $this->record = new RecordapiRepository();
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
        return $this->record->getMsg($this->apirecord);
    }
}


//实例化
$syncApiRecord = new SyncApiRecord();
//获取数据
//while (true){
    $message = $syncApiRecord->getMsg();
dd($message);
    //进入队列
$message ='{"a":1,"b":2}';
    $syncApiRecord->getAPirecord($message);
//}
