<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Newsletter
 */
class Newsletter implements ResourceInterface, Slugable, NewsletterInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var NodeInterface
     */
    private $content;

    /**
     * @var \DateTime|null
     */
    private $startAt;

    /**
     * @var \DateTime|null
     */
    private $finishAt;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var string
     */
    private $template;

    /**
     * @var Collection
     */
    private $groups;

    /**
     * @var Collection
     */
    private $receivers;

    /**
     * @var string
     */
    private $state = NewsletterInterface::STATE_CREATED;

    /*
     * Collection
     */
    private $attachments;

    /**
     * Constructor
     */
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Newsletter
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
     * @return Collection
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
