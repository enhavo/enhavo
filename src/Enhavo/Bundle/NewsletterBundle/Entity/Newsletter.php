<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Enhavo\Bundle\AppBundle\Model\TimestampableTrait;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;

/**
 * Newsletter
 */
class Newsletter implements Slugable, NewsletterInterface, Timestampable
{
    use TimestampableTrait;
    private ?int $id = null;
    private ?string $slug = null;
    private ?string $subject = null;
    private ?NodeInterface $content = null;
    private ?\DateTime $startAt = null;
    private ?\DateTime $finishAt = null;
    private ?string $template = null;
    private Collection $groups;
    private Collection $receivers;
    private string $state = NewsletterInterface::STATE_CREATED;
    private Collection $attachments;

    public function __construct()
    {
        $this->receivers = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Newsletter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @return Newsletter
     */
    public function setContent(NodeInterface $content)
    {
        $this->content = $content;
        if ($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
            $content->setResource($this);
        }

        return $this;
    }

    /**
     * Get content
     *
     * @return NodeInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getFinishAt(): ?\DateTime
    {
        return $this->finishAt;
    }

    public function setFinishAt(?\DateTime $finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup($group)
    {
        $this->groups->add($group);
    }

    public function removeGroup($group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * @return $this|NewsletterInterface
     */
    public function addReceiver(Receiver $receiver)
    {
        $this->receivers[] = $receiver;
        $receiver->setNewsletter($this);

        return $this;
    }

    public function removeReceiver(Receiver $receiver)
    {
        $this->receivers->removeElement($receiver);
        $receiver->setNewsletter(null);
    }

    /**
     * @return ArrayCollection|Collection|Receiver[]
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    public function addAttachment(FileInterface $attachment)
    {
        $this->attachments[] = $attachment;
    }

    public function removeAttachment(FileInterface $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * @return ArrayCollection|Collection|FileInterface[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    public function isPrepared(): bool
    {
        return NewsletterInterface::STATE_CREATED != $this->state;
    }

    public function isSent()
    {
        return NewsletterInterface::STATE_SENT == $this->state;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
