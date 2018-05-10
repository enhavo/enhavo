<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 13:47
 */

namespace Enhavo\Bundle\AppBundle\Metadata;


interface ParserInterface
{
    public function parse(array &$metadataArray, $configuration);
}