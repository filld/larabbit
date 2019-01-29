<?php namespace Filld\Amqp;

use Illuminate\Config\Repository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class Request extends Context
{

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $queueInfo;

    /**
     *
     */
    public function connect()
    {
        $this->connection = new AMQPSSLConnection(
            $this->getProperty('host'),
            $this->getProperty('port'),
            $this->getProperty('username'),
            $this->getProperty('password'),
            $this->getProperty('vhost'),
            $this->getProperty('ssl_options'),
            $this->getProperty('connect_options')
        );

        $this->channel = $this->connection->channel();
    }

    /**
     * @throws Exception\Configuration
     */
    public function setup()
    {
        $this->connect();

        // Name of the queue the service listens on.  This is optional as a producer won't define one
        $queue = $this->getProperty('queue');

        $exchanges = $this->getProperty('exchanges');

        if (empty($exchanges)) {
            throw new Exception\Configuration('Please check your settings, exchanges is not defined.');
        }

        // Only attempt to create a queue if the property has been defined
        if (!empty($queue)) {
            /*
                name: $queue
                passive: false
                durable: true // the queue will survive server restarts
                exclusive: false // queue is deleted when connection closes
                auto_delete: false //the queue won't be deleted once the channel is closed.
                nowait: false // Doesn't wait on replies for certain things.
                parameters: array // Extra data, like high availability params
            */

            /** @var ['queue name', 'message count',] queueInfo */
            $this->queueInfo = $this->channel->queue_declare(
                $queue,
                $this->getProperty('queue_passive'),
                $this->getProperty('queue_durable'),
                $this->getProperty('queue_exclusive'),
                $this->getProperty('queue_auto_delete'),
                $this->getProperty('queue_nowait'),
                $this->getProperty('queue_properties')
            );
        }

        foreach($exchanges as $exchange) {
            /*
                name: $exchange
                type: topic|direct
                passive: false
                durable: true // the exchange will survive server restarts
                auto_delete: false //the exchange won't be deleted once the channel is closed.
            */

            $this->channel->exchange_declare(
                $exchange['exchange'],
                $exchange['exchange_type'],
                $exchange['exchange_passive'],
                $exchange['exchange_durable'],
                $exchange['exchange_auto_delete'],
                $exchange['exchange_internal'],
                $exchange['exchange_nowait'],
                $exchange['exchange_properties']
            );

            if (!empty($exchange['routing'])
                && (!empty($queue) || $this->getProperty('queue_force_declare'))) {
                foreach ($exchange['routing'] as $route) {
                    // Add each route specified
                    $this->channel->queue_bind($queue ?: $this->queueInfo[0], $exchange['exchange'], $route);
                }
            }
        }

        // clear at shutdown
        register_shutdown_function([get_class(), 'shutdown'], $this->channel, $this->connection);
    }

    /**
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return int
     */
    public function getQueueMessageCount()
    {
        if (is_array($this->queueInfo)) {
            return $this->queueInfo[1];
        }
        return 0;
    }

    /**
     * @param AMQPChannel          $channel
     * @param AMQPStreamConnection $connection
     */
    public static function shutdown(AMQPChannel $channel, AMQPStreamConnection $connection)
    {
        $channel->close();
        $connection->close();
    }

}
