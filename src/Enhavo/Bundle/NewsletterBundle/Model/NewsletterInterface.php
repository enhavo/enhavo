<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;

interface NewsletterInterface
{
    public const STATE_CREATED = 'created';
    public const STATE_PREPARED = 'prepared';
    public const STATE_SENDING = 'sending';
    public const STATE_SENT = 'sent';

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return NewsletterInterface
     */
    public function setSubject($subject);

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * @return void
     */
    public function setContent(NodeInterface $content);

    /**
     * @return NodeInterface
     */
    public function getContent();

    public function getTemplate(): ?string;

    public function setTemplate(?string $template);

    /**
     * @return bool
     */
    public function isPrepared();

    /**
     * @return bool
     */
    public function isSent();

    /**
     * @return string
     */
    public function getState();

    public function setState(string $state);

    /**
     * Add receiver
     *
     * @return NewsletterInterface
     */
    public function addReceiver(Receiver $receiver);

    /**
     * Remove receiver
     */
    public function removeReceiver(Receiver $receiver);

    /**
     * Get group
     *
     * @return Collection|Receiver[]
     */
    public function getReceivers();

    /**
     * Add attachments
     */
    public function addAttachment(FileInterface $attachments);

    /**
     * Remove attachments
     */
    public function removeAttachment(FileInterface $attachments);

    /**
     * Get attachments
     *
     * @return Collection
     */
    public function getAttachments();

    public function getStartAt(): ?\DateTime;

    public function setStartAt(?\DateTime $startAt): void;

    public function getFinishAt(): ?\DateTime;

    public function setFinishAt(?\DateTime $finishAt): void;

    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(?\DateTime $createdAt): void;
}
