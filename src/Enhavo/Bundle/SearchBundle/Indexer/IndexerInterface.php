<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 19:37
 */

namespace Enhavo\Bundle\SearchBundle\Indexer;

interface IndexerInterface
{
    public function getIndexes($data, array $options = []);
}