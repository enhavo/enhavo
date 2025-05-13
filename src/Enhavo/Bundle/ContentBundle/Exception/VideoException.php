<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Exception;

class VideoException extends \Exception
{
    public static function noProviderFound($url)
    {
        return new self(sprintf('No provider found for url "%s"', $url));
    }
}
