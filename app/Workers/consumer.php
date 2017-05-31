<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/vendor/autoload.php';
$config = require(__DIR__ .'/config/amqp.php') ;
$exchange = 'router';
$queue = 'msgs';
$consumerTag = 'consumer';
$connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['pass'], $config['vhost']);
$channel = $connection->channel();
$channel->queue_declare($queue, false, true, false, false);

$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);
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
/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

register_shutdown_function('shutdown', $channel, $connection);
// Loop as long as the channel has callbacks registered
while (count($channel->callbacks)) {
    $read = array($connection->getSocket()); // add here other sockets that you need to attend
    $write = null;
    $except = null;
    if (false === ($changeStreamsCount = stream_select($read, $write, $except, 60))) {
        /* Error handling */
    } elseif ($changeStreamsCount > 0) {
        $channel->wait();
    }
}