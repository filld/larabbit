<?php namespace Filld\Amqp;

use Filld\Amqp\Constants\AmqpConstants;
use \PhpAmqpLib\Wire\AMQPTable;

class DelayedMQMessage extends MQMessage
{
    public function __construct(MQPayload $payload, $delay = 0, array $properties = [])
    {
        if(!array_key_exists(AmqpConstants::HEADER_APPLICATION_HEADERS, $properties)) {
            $properties[AmqpConstants::HEADER_APPLICATION_HEADERS] = new AMQPTable(['x-delay' => $delay]);
        }

        parent::__construct($payload, $properties);
    }
}
