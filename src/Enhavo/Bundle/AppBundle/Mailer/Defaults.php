<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     */
    public function __construct(string $mailFrom, string $mailSenderName, string $mailTo)
    {
        $this->mailFrom = $mailFrom;
        $this->mailSenderName = $mailSenderName;
        $this->mailTo = $mailTo;
    }

    public function getMailFrom(): string
    {
        return $this->mailFrom;
    }

    public function getMailSenderName(): string
    {
        return $this->mailSenderName;
    }

    public function getMailTo(): string
    {
        return $this->mailTo;
    }
}
