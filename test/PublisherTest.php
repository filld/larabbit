<?php namespace Filld\Amqp\Test;

use Filld\Amqp\Amqp;
use Filld\Amqp\Constants\AmqpConstants;
use Filld\Amqp\MQPayload;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class PublisherTest extends \PHPUnit_Framework_TestCase
{

    public function testExchange()
    {
        $this->assertEquals('exchange', AmqpConstants::EXCHANGE);
    }

}