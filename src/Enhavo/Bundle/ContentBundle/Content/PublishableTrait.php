<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Content;

trait PublishableTrait
{
    /** @var bool */
    protected $public = false;

    /** @var \DateTime */
    protected $publicationDate;

    /** @var \DateTime */
    protected $publishedUntil;

    public function setPublic($public)
    {
        $this->public = $public;
    }

    public function isPublic()
    {
        return (bool) $this->public;
    }

    public function setPublicationDate(?\DateTime $date = null)
    {
        $this->publicationDate = $date;
    }

    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    public function setPublishedUntil(?\DateTime $date = null)
    {
        $this->publishedUntil = $date;
    }

    public function getPublishedUntil()
    {
        return $this->publishedUntil;
    }

    public function isPublished()
    {
        if (false === $this->isPublic()) {
            return false;
        }

        $now = new \DateTime();
        if ($now > $this->publicationDate) {
            if (null !== $this->publishedUntil && $now > $this->publishedUntil) {
                return false;
            }

            return true;
        }

        return false;
    }
}
