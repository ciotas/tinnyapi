<?php

namespace App\Controller;
use App\Repositories\RecordapiRepository;
use ERedis as Redis;
use Rabbitmq;

class RecordapiController extends BaseController
{
    protected $record;

    public function __construct()
    {
        $this->record = new RecordapiRepository();
    }


    public function record()
    {
        $request_api = $_POST['request_api'];
        $post_data = $_POST['post_data'];
        $result_data = $_POST['result_data'];
        $spendtime = $_POST['spendtime'];

//        $param = [
//            'request_api'=>$request_api,
//            'post_data'=>$post_data,
//            'result_data'=>$result_data,
//            'spendtime'=>$spendtime,
//        ];

        $param = [
            'request_api'=>'http://www/baidu.com',
            'post_data'=>json_encode(['a'=>1,'b'=>2]),
            'result_data'=>json_encode(['c'=>3,'d'=>4]),
            'spendtime'=>15,
        ];
        $this->record->recordapi($param);
        return json_encode(['status'=>'ok']);
    }

    /**
     *
     */
    public function amqpPublisher()
    {
        $queue = 'msgs';
        $amqp = new Rabbitmq($queue);

        $message = '{"status"=>1,"msg"=>"send success!"}';

        $amqp->onPublish($message);
        $amqp->onClose();

    }


}