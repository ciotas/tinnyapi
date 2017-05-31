<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbitmq {

    protected $channel;
    protected $connection;
    protected $queue;
    protected $exchange;

    public function __construct($queue)
    {
        $this->queue = $queue;
        $this->exchange = 'router';
        $config = require BASE_PATH.'/config/rabbitmq.php';
        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['pass'], $config['vhost']);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);

        $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $this->channel->queue_bind($this->queue, $this->exchange);
    }

    /**
     * @param $messageBody
     * @param $channel
     * @param $exchange
     */
    public function onPublish($messageBody)
    {
        //application/json
        $message = new AMQPMessage($messageBody, array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, $this->exchange);

    }

    /**
     * @param $queue
     * @param $consumerTag
     */
    public function basicConsume($queue, $consumerTag)
    {
        $this->channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');
        register_shutdown_function('onClose', $this->channel, $this->connection);

    }

    /**
     *
     */
    public function callBacks()
    {
        // Loop as long as the channel has callbacks registered
        while (count($this->channel->callbacks)) {
            $read = array($this->connection->getSocket()); // add here other sockets that you need to attend
            $write = null;
            $except = null;
            if (false === ($changeStreamsCount = stream_select($read, $write, $except, 60))) {
                /* Error handling */
            } elseif ($changeStreamsCount > 0) {
                $this->channel->wait();
            }
        }
    }
    /**
     * @param $channel
     * @param $connection
     */
    public function onClose()
    {
        $this->channel->close();
        $this->connection->close();
    }


}