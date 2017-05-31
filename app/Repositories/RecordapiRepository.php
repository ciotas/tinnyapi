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
        Log::debug('First Debug Info.');
//        Redis::RPUSH($this->apirecord,json_encode($param));
    }

}