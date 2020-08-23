<?php
namespace App\Message;

use Bernard\Message;

abstract class AbstractMessage implements Message
{
    protected $message;

    public function getName()
    {
        return get_class($this);
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}