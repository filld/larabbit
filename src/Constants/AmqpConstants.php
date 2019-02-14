<?php
namespace Filld\Amqp\Constants;


class AmqpConstants
{
    /** @deprecated Use PROPERTY_EXCHANGE instead. */
    const EXCHANGE               = 'exchange';
    const PROPERTY_EXCHANGE      = 'exchange';
    const PROPERTY_EXCHANGE_TYPE = 'exchange_type';

    const EXCHANGE_TYPE_TOPIC  = 'topic';
    const EXCHANGE_TYPE_FANOUT = 'fanout';
    const EXCHANGE_TYPE_DIRECT = 'direct';

    const HEADER_CONTENT_TYPE        = 'content_type';
    const HEADER_APPLICATION_HEADERS = 'application_headers';

    const CONTENT_TYPE_JSON   = 'application/json';
}