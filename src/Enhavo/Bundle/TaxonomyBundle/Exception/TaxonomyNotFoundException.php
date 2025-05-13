<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Exception;

class TaxonomyNotFoundException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = sprintf('Could not find taxonomy "%s". Please add it to the taxonomies configuration under enhavo_taxonomy.taxonomies and execute enhavo:init', $message);
        parent::__construct($message, $code, $previous);
    }
}
