<?php

namespace Enhavo\Bundle\ContentBundle\Sitemap;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

/**
 * CollectorInterface.php
 *
 * @since 05/07/16
 * @author gseidel
 */
interface CollectorInterface extends TypeInterface
{
    public function setOptions($options);

    public function getUrls();
}