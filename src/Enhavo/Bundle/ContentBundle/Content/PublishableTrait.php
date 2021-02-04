<?php
/**
 * PublishableTrait.php
 *
 * @since 15/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Content;


trait PublishableTrait
{
    /** @var boolean */
    protected $public = false;

    /** @var \DateTime */
    protected $publicationDate;

    /** @var \DateTime */
    protected $publishedUntil;

    /**
     * {@inheritdoc}
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublic()
    {
        return (boolean)$this->public;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicationDate(\DateTime $date = null)
    {
        $this->publicationDate = $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishedUntil(\DateTime $date = null)
    {
        $this->publishedUntil = $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishedUntil()
    {
        return $this->publishedUntil;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        if($this->isPublic() === false) {
            return false;
        }

        $now = new \DateTime();
        if($now > $this->publicationDate) {
            if($this->publishedUntil !== null && $now > $this->publishedUntil) {
                return false;
            }
            return true;
        }
        return false;
    }
}
