<?php

namespace App\Workers;
use Rabbitmq;

class ConsumeAPiRecord
{
    protected $queue;
    protected $apirecord;

    /**
     * ConsumeAPiRecord constructor.
     */
    public function __construct()
    {
        $this->exchange = 'router';
        $this->queue = 'msgs';
        $this->consumerTag = 'consumer';
        $this->rabbitmq_config = '/config/amqp.php';
    }

    public function consume()
    {
        $amqp = new Rabbitmq($this->queue);
        $amqp->basicConsume($this->queue,$this->consumerTag);
        $amqp->callBacks();
    }

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     * 这里是主要接收逻辑
     */
    function process_message($message){
        echo "\n--------\n";
        echo $message->body;
        echo "\n--------\n";
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }

}

$consumeAPiRecord = new ConsumeAPiRecord();

$consumeAPiRecord->consume();
