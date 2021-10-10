<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait MailTrait
{
    /** @var ?string */
    private $mailSubject;

    /** @var ?string */
    private $mailFrom;

    /** @var ?string */
    private $mailName;

    /** @var ?string */
    private $mailContext;

    /** @var ?string */
    private $mailTemplate;

    /** @var ?string */
    private $mailContentType;

    /**
     * @return string|null
     */
    public function getMailSubject(): ?string
    {
        return $this->mailSubject;
    }

    /**
     * @param string|null $mailSubject
     */
    public function setMailSubject(?string $mailSubject): void
    {
        $this->mailSubject = $mailSubject;
    }

    /**
     * @return string|null
     */
    public function getMailName(): ?string
    {
        return $this->mailName;
    }

    /**
     * @param string|null $mailName
     */
    public function setMailName(?string $mailName): void
    {
        $this->mailName = $mailName;
    }

    /**
     * @return string|null
     */
    public function getMailContext(): ?string
    {
        return $this->mailContext;
    }

    /**
     * @param string|null $mailContext
     */
    public function setMailContext(?string $mailContext): void
    {
        $this->mailContext = $mailContext;
    }

    /**
     * @return string|null
     */
    public function getMailTemplate(): ?string
    {
        return $this->mailTemplate;
    }

    /**
     * @param string|null $mailTemplate
     */
    public function setMailTemplate(?string $mailTemplate): void
    {
        $this->mailTemplate = $mailTemplate;
    }

    /**
     * @return string|null
     */
    public function getMailContentType(): ?string
    {
        return $this->mailContentType;
    }

    /**
     * @param string|null $mailContentType
     */
    public function setMailContentType(?string $mailContentType): void
    {
        $this->mailContentType = $mailContentType;
    }

    /**
     * @return string|null
     */
    public function getMailFrom(): ?string
    {
        return $this->mailFrom;
    }

    /**
     * @param string|null $mailFrom
     */
    public function setMailFrom(?string $mailFrom): void
    {
        $this->mailFrom = $mailFrom;
    }
}
