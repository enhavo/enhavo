<?php

namespace Enhavo\Bundle\NewsletterBundle\Entity;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;
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
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var ContainerInterface
     */
    private $content;

    /**
     * @var boolean
     */
    private $sent;

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
     * Set title
     *
     * @param string $title
     *
     * @return Newsletter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param ContainerInterface $content
     *
     * @return Newsletter
     */
    public function setContent(ContainerInterface $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return ContainerInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sent
     *
     * @param boolean $sent
     *
     * @return Newsletter
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return boolean
     */
    public function getSent()
    {
        return $this->sent;
    }
}
