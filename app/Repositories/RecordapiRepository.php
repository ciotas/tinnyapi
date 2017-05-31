<?php

namespace App\Repositories;
use ERedis as Redis;

class RecordapiRepository
{
    protected $apirecord;

    /**
     * RecordapiRepository constructor.
     */
    public function __construct()
    {
        $this->apirecord = 'record_java_error_api_request_data_list';
    }


    /**
     * @param $param
     */
    public function recordapi($param)
    {
        Redis::lpush($this->apirecord,json_encode($param));
    }

    public function getMsg($key)
    {
        return Redis::rpop($key);
    }

}