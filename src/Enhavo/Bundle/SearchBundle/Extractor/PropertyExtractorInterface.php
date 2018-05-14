<?php

namespace Enhavo\Bundle\SearchBundle\Extractor;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

/**
 * Extractor.php
 * Gets the raw data of a resource
 */
interface PropertyExtractorInterface extends TypeInterface
{
    /**
     * @param $value
     * @param array $options
     */
    public function extract($value, $options = []);
}