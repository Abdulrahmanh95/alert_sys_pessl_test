<?php

namespace App\Message\Mail;

class SendAlertEmail extends AbstractEmail
{
    /**
     * @var array
     */
    private $user;

    /**
     * @var array
     */
    private $warnings;

    public function __construct($user, array $warnings = [], $subject = 'Station Alert Check!')
    {
        $this->user     = $user;
        $this->subject  = $subject;
        $this->warnings = $warnings;

        $this->message = 'Dears,<br/><br/>Please check the following warnings:<br/><br/><ul>';
        foreach ($warnings as $warning) {
            $this->message .= "<li>" . $warning . "</li>";
        }
        $this->message .= '</ul><br/><br/>Thanks,';
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getHandledTypes()
    {
        return array_keys($this->warnings);
    }

    public function getWarnings()
    {
        return $this->warnings;
    }
}