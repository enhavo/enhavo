<?php

/**
 * DefaultRenderer.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Preview;

use Symfony\Component\HttpFoundation\Response;

class DefaultRenderer
{
    public function render($document)
    {
        return new Response(get_class($document));
    }
}