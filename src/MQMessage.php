<?php namespace Filld\Amqp;

use Filld\Amqp\Constants\AmqpConstants;
use \PhpAmqpLib\Message\AMQPMessage;

class MQMessage extends AMQPMessage
{

    public function __construct(MQPayload $payload, array $properties = [])
    {
        $body = $payload->jsonRender();

        if(!array_key_exists(AmqpConstants::HEADER_CONTENT_TYPE, $properties)) {
            $properties[AmqpConstants::HEADER_CONTENT_TYPE] = AmqpConstants::CONTENT_TYPE_JSON;
        }

        parent::__construct($body, $properties);
    }
}
