<?php

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
