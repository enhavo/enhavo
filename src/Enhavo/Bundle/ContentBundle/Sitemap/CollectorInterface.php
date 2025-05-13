<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Sitemap;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

/**
 * CollectorInterface.php
 *
 * @since 05/07/16
 *
 * @author gseidel
 */
interface CollectorInterface extends TypeInterface
{
    public function setOptions($options);

    public function getUrls();
}
