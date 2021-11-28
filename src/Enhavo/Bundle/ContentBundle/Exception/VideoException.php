<?php

namespace Enhavo\Bundle\ContentBundle\Exception;

class VideoException
{
    public static function noProviderFound($url)
    {
        return new self::(sprintf('No provider found for url "%s"', $url));
    }
}
