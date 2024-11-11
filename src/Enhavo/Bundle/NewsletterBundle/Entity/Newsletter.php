<?php

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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
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
     * @param NodeInterface $content
     *
     * @return Newsletter
     */
    public function setContent(NodeInterface $content)
    {
        $this->content = $content;
        if($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime|null $startAt
     */
    public function setStartAt(?\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getFinishAt(): ?\DateTime
    {
        return $this->finishAt;
    }

    /**
     * @param \DateTime|null $finishAt
     */
    public function setFinishAt(?\DateTime $finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    /**
     * @return string
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * @param string|null $template
     */
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

    /**
     * @param mixed $group
     */
    public function addGroup($group)
    {
        $this->groups->add($group);
    }

    /**
     * @param mixed $group
     */
    public function removeGroup($group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * @param Receiver $receiver
     * @return $this|NewsletterInterface
     */
    public function addReceiver(Receiver $receiver)
    {
        $this->receivers[] = $receiver;
        $receiver->setNewsletter($this);
        return $this;
    }

    /**
     * @param Receiver $receiver
     */
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

    /**
     * @param FileInterface $attachment
     */
    public function addAttachment(FileInterface $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @param FileInterface $attachment
     */
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

    /**
     * @return bool
     */
    public function isPrepared(): bool
    {
        return $this->state != NewsletterInterface::STATE_CREATED;
    }

    public function isSent()
    {
        return $this->state == NewsletterInterface::STATE_SENT;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }
}
