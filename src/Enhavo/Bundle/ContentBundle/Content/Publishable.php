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

interface Publishable
{
    /**
     * Decide if resource is public or not
     */
    public function setPublic($bool);

    /**
     * Return if resource is public
     */
    public function isPublic();

    /**
     * Set publication date
     */
    public function setPublicationDate(\DateTime $date);

    /**
     * Get publication date
     */
    public function getPublicationDate();

    /**
     * If set, will remove publish status at this date
     */
    public function setPublishedUntil(\DateTime $date);

    /**
     * Get published until date
     */
    public function getPublishedUntil();

    /**
     * Return if resource is published at the moment
     */
    public function isPublished();
}
