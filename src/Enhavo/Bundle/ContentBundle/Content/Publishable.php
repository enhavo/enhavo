<?php

/**
 * Publishable.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Content;

interface Publishable
{
    /**
     * Decide if resource is public or not
     *
     * @param $bool
     * @return mixed
     */
    public function setPublic($bool);

    /**
     * Return if resource is public
     *
     * @return mixed
     */
    public function isPublic();

    /**
     * Set publication date
     *
     * @param \DateTime $date
     * @return mixed
     */
    public function setPublicationDate(\DateTime $date);

    /**
     * Get publication date
     *
     * @return mixed
     */
    public function getPublicationDate();

    /**
     * If set, will remove publish status at this date
     *
     * @param \DateTime $date
     * @return mixed
     */
    public function setHideAt(\DateTime $date);

    /**
     * Get hide at date
     *
     * @return mixed
     */
    public function getHideAt();

    /**
     * Return if resource is published at the moment
     *
     * @return mixed
     */
    public function isPublished();
}