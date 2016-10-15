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
    /**
     * @var boolean
     */
    protected $public;

    /**
     * @var \DateTime
     */
    protected $publicationDate;

    /**
     * @var \DateTime
     */
    protected $hideAt;

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
    public function setPublicationDate(\DateTime $date)
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
    public function setHideAt(\DateTime $date)
    {
        $this->hideAt = $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getHideAt()
    {
        return $this->hideAt;
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
            if($this->hideAt !== null && $now > $this->hideAt) {
                return false;
            }
            return true;
        }
        return false;
    }
}