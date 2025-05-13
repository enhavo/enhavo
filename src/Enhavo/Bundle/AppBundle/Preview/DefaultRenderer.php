<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Preview;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author gseidel
 */
class DefaultRenderer
{
    public function render($document)
    {
        return new Response(get_class($document));
    }
}
