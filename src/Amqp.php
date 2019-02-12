<?php namespace Filld\Amqp;

use Closure;
use Filld\Amqp\Constants\AmqpConstants;
use Filld\Amqp\Request;
use Filld\Amqp\Message;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class Amqp
{

    /* @var Publisher $publisher */
    private $publisher;
    /* @var array $properties */
    private $properties = [];

    public function __destruct()
    {
        if ($this->publisher) {
            Request::shutdown($this->publisher->getChannel(), $this->publisher->getConnection());
        }
    }

    /**
     * @param string $routing
     * @param mixed  $message
     * @param array  $properties
     */
    public function publish($routing, $message, array $properties = [])
    {
        $properties['routing'] = $routing;

        if ($this->properties != $properties) {
            $this->publisher = app()->make('Filld\Amqp\Publisher');
            $this->properties = $properties;
            $this->publisher
                ->mergeProperties($this->properties)
                ->setup();
        }

        if (is_string($message)) {
            $message = new Message($message, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
        }

        $this->publisher->publish($routing, $message);
    }

    /**
     * @param string  $queue
     * @param Closure $callback
     * @param array   $properties
     * @throws Exception\Configuration
     */
    public function consume($queue, Closure $callback, $properties = [])
    {
        $properties['queue'] = $queue;

        /* @var Consumer $consumer */
        $consumer = app()->make('Filld\Amqp\Consumer');
        $consumer
            ->mergeProperties($properties)
            ->setup();

        $consumer->consume($queue, $callback);
        Request::shutdown($consumer->getChannel(), $consumer->getConnection());
    }

    /**
     * @param string $body
     * @param array  $properties
     * @return \Filld\Amqp\Message
     */
    public function message($body, $properties = [])
    {
        return new Message($body, $properties);
    }


    /**
     * @param string $exchange
     * @param string $routingKey
     * @param mixed  $message
     * @param array  $properties
     */
    public function publishTopic($exchange, $routingKey, $message, array $properties = [])
    {
        self::publishAny($exchange, AmqpConstants::EXCHANGE_TYPE_TOPIC, $routingKey, $message, $properties);
    }

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param mixed  $message
     * @param array  $properties
     */
    public function publishDirect($exchange, $routingKey, $message, array $properties = [])
    {
        self::publishAny($exchange, AmqpConstants::EXCHANGE_TYPE_DIRECT, $routingKey, $message, $properties);
    }

    /**
     * @param string $exchange
     * @param string $routingKey
     * @param mixed  $message
     * @param array  $properties
     */
    public function publishFanout($exchange, $routingKey, $message, array $properties = [])
    {
        self::publishAny($exchange, AmqpConstants::EXCHANGE_TYPE_FANOUT, $routingKey, $message, $properties);
    }

    /**
     * @param string $exchange
     * @param        $exchangeType
     * @param string $routingKey
     * @param mixed  $message
     * @param array  $properties
     */
    protected function publishAny($exchange, $exchangeType, $routingKey, $message, array $properties = [])
    {
        $properties[AmqpConstants::PROPERTY_EXCHANGE] = $exchange;
        $properties[AmqpConstants::PROPERTY_EXCHANGE_TYPE] = $exchangeType;

        self::publish($routingKey, $message, $properties);
    }
}