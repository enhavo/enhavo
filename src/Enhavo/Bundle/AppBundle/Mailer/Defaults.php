<?php

namespace Enhavo\Bundle\AppBundle\Mailer;

class Defaults
{
    /** @var string */
    private $mailFrom;

    /** @var string */
    private $mailSenderName;

    /** @var string */
    private $mailTo;

    /**
     * Defaults constructor.
     * @param string $mailFrom
     * @param string $mailSenderName
     * @param string $mailTo
     */
    public function __construct(string $mailFrom, string $mailSenderName, string $mailTo)
    {
        $this->mailFrom = $mailFrom;
        $this->mailSenderName = $mailSenderName;
        $this->mailTo = $mailTo;
    }

    /**
     * @return string
     */
    public function getMailFrom(): string
    {
        return $this->mailFrom;
    }

    /**
     * @return string
     */
    public function getMailSenderName(): string
    {
        return $this->mailSenderName;
    }

    /**
     * @return string
     */
    public function getMailTo(): string
    {
        return $this->mailTo;
    }
}
