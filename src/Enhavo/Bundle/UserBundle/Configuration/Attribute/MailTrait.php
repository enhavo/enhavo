<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait MailTrait
{
    private bool $mailEnabled = true;
    private ?string $mailSubject = null;
    private ?string $mailFrom = null;
    private ?string $mailSenderName = null;
    private ?string $mailContext = null;
    private ?string $mailTemplate = null;
    private ?string $mailContentType = null;

    public function isMailEnabled(): bool
    {
        return $this->mailEnabled;
    }

    public function setMailEnabled(bool $mailEnabled): void
    {
        $this->mailEnabled = $mailEnabled;
    }

    public function getMailSubject(): ?string
    {
        return $this->mailSubject;
    }

    public function setMailSubject(?string $mailSubject): void
    {
        $this->mailSubject = $mailSubject;
    }

    public function getMailSenderName(): ?string
    {
        return $this->mailSenderName;
    }

    public function setMailSenderName(?string $mailSenderName): void
    {
        $this->mailSenderName = $mailSenderName;
    }

    public function getMailContext(): ?string
    {
        return $this->mailContext;
    }

    public function setMailContext(?string $mailContext): void
    {
        $this->mailContext = $mailContext;
    }

    public function getMailTemplate(): ?string
    {
        return $this->mailTemplate;
    }

    public function setMailTemplate(?string $mailTemplate): void
    {
        $this->mailTemplate = $mailTemplate;
    }

    public function getMailContentType(): ?string
    {
        return $this->mailContentType;
    }

    public function setMailContentType(?string $mailContentType): void
    {
        $this->mailContentType = $mailContentType;
    }

    public function getMailFrom(): ?string
    {
        return $this->mailFrom;
    }

    public function setMailFrom(?string $mailFrom): void
    {
        $this->mailFrom = $mailFrom;
    }
}
