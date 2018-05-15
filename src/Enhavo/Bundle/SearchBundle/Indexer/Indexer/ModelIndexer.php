<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:30
 */

namespace Enhavo\Bundle\SearchBundle\Indexer\Indexer;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Indexer\IndexerInterface;

/*
 * Prepares fields of type model for indexing
 */
class ModelIndexer extends AbstractType implements IndexerInterface
{
    public function getIndexes($value, array $options = [])
    {
        if($value !== null) {
            return $this->container->get('enhavo_search.indexer.indexer')->getIndexes($value);
        }
        return [];
    }

    public function getType()
    {
        return 'model';
    }
}