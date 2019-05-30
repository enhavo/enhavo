<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-27
 * Time: 00:06
 */

namespace Enhavo\Bundle\TaxonomyBundle\Exception;

use Exception;
use Throwable;

class TaxonomyNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Could not found taxonomy "%s". Please add it to the taxonomies configuration under enhavo_taxonomy.taxonies and execute enhavo:init', $message);
        parent::__construct($message, $code, $previous);
    }
}
