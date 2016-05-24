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
    public function setPublic($bool);
    public function getPublic();
    public function setPublicationDate(\DateTime $publicationDate);
    public function getPublicationDate();
}