<?php namespace Filld\Amqp\Test;

use Filld\Amqp\MQPayload;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class MQPayloadTest extends \PHPUnit_Framework_TestCase
{

    public function testMQPayloadV1()
    {
        $payload = new MQPayload('test', ["data" => 1]);
        $payload->setVersion(1);
        $testJsonV1 = '{"version":1,"event":"test","status":true,"message":null,"data":{"data":1}}';
        $this->assertJson($testJsonV1, json_encode($payload));
    }

    public function testMQPayloadV2()
    {
        $payload = new MQPayload('test', ["data" => 1]);
        $testJsonV2 = '{"version":2,"event":"test","status":true,"message":null,"test":{"data":1}}';
        $this->assertJson($testJsonV2, json_encode($payload));
    }

}