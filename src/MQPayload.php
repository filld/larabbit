<?php
/**
 * Created by PhpStorm.
 * User: ryebread
 * Date: 6/27/18
 * Time: 2:54 PM
 */

namespace Filld\Amqp;

class MQPayload implements \JsonSerializable
{
    protected $event;

    protected $message;

    protected $data;

    protected $payload;

    protected $status = true;

    protected $version = 2;

    public function __construct($event, $data = [])
    {
        $this->event = $event;
        $this->data = $data;
    }

    public function generate()
    {
        $this->payload = [
            'version' => $this->version,
            'event' => $this->event,
            'status' => $this->status,
            'message' => $this->message,
        ];

        if($this->version < 2) {
            $this->payload['data'] = $this->data;
        } else {
            $this->payload[$this->event] = $this->data;
        }

        return $this->payload;
    }

    public function jsonRender()
    {
        return json_encode($this);
    }

    public function jsonSerialize()
    {
        return $this->generate();
    }

    public function getData() {
        return $this->data;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }
}