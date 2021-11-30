<?php

namespace Enhavo\Bundle\ContentBundle\Exception;

class VideoException extends \Exception
{
    public static function noProviderFound($url)
    {
        return new self(sprintf('No provider found for url "%s"', $url));
    }
}
