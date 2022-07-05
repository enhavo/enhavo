<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait MailTrait
{
    private bool $mailEnabled = true;
    private ?string $mailSubject;
    private ?string $mailFrom;
    private ?string $mailSenderName;
    private ?string $mailContext;
    private ?string $mailTemplate;
    private ?string $mailContentType;

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
