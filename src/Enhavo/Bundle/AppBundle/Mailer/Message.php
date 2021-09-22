<?php

namespace Enhavo\Bundle\AppBundle\Mailer;

class Message
{
    const CONTENT_TYPE_MIXED = 'multipart/mixed';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_PLAIN = 'text/plain';

    /** @var string */
    private $from;

    /** @var string */
    private $senderName;

    /** @var string */
    private $to;

    /** @var string */
    private $subject;

    /** @var string */
    private $template;

    /** @var array */
    private $context;

    /** @var array */
    private $attachments;

    /** @var string */
    private $contentType;

    /** @var string */
    private $content;

    public function __construct(
        string $from = null,
        string $senderName = null,
        string $to = null,
        string $subject = null,
        string $template = null,
        array $context = [],
        array $attachments = [],
        string $contentType = self::CONTENT_TYPE_PLAIN
    ) {
        $this->from = $from;
        $this->senderName = $senderName;
        $this->to = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->context = $context;
        $this->attachments = $attachments;
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(?string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * @param mixed $context
     */
    public function addContext($context)
    {
        $this->context[] = $context;
    }

    /**
     * @param mixed $context
     */
    public function removeContext($context)
    {
        if (false !== $key = array_search($context, $this->context, true)) {
            array_splice($this->context, $key, 1);
        }
    }

    /**
     * @param mixed $attachment
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @param mixed $attachment
     */
    public function removeAttachment($attachment)
    {
        if (false !== $key = array_search($attachment, $this->attachments, true)) {
            array_splice($this->attachments, $key, 1);
        }
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}
