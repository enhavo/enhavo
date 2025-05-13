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

class Message
{
    public const CONTENT_TYPE_MIXED = 'multipart/mixed';
    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_PLAIN = 'text/plain';

    /** @var string */
    private $content;

    public function __construct(
        private ?string $from = null,
        private ?string $senderName = null,
        private string|array|null $to = null,
        private ?string $subject = null,
        private ?string $template = null,
        private array $context = [],
        private array $attachments = [],
        private string $contentType = self::CONTENT_TYPE_PLAIN,
        private array $cc = [],
        private array $bcc = [],
    ) {
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function setSenderName(?string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getTo(): string|array|null
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string|array|null $to): void
    {
        $this->to = $to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function addContext($context)
    {
        $this->context[] = $context;
    }

    public function removeContext($context)
    {
        if (false !== $key = array_search($context, $this->context, true)) {
            array_splice($this->context, $key, 1);
        }
    }

    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;
    }

    public function removeAttachment(Attachment $attachment)
    {
        if (false !== $key = array_search($attachment, $this->attachments, true)) {
            array_splice($this->attachments, $key, 1);
        }
    }

    /**
     * @return array<Attachment>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(array $cc): void
    {
        $this->cc = $cc;
    }

    public function addCc(string $cc)
    {
        $this->cc[] = $cc;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function setBcc(array $bcc): void
    {
        $this->bcc = $bcc;
    }

    public function addBcc(string $bcc)
    {
        $this->bcc[] = $bcc;
    }
}
