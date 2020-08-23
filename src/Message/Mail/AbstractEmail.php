<?php

namespace App\Message\Mail;

use App\Message\AbstractMessage;

abstract class AbstractEmail extends AbstractMessage
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

}