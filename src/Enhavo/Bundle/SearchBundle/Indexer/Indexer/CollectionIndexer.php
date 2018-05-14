<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Indexer\Indexer;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\SearchBundle\Indexer\Index;
use Enhavo\Bundle\SearchBundle\Indexer\IndexerInterface;

class CollectionIndexer extends AbstractType implements IndexerInterface
{
    public function getIndexes($value, array $options = [])
    {
        $data = [];
        if($value) {
            foreach($value as $item) {
                if(is_string($item)) {
                    $index = new Index();
                    $index->setWeight($this->getOption('weight', $options, 0));
                    $index->setValue(trim($value));
                    $data[] = $index;
                } elseif(is_object($value)) {
                    $indexes = $this->container->get('enhavo_search.indexer.indexer')->getIndexes($item);
                    foreach($indexes as $index) {
                        $data[] = $index;
                    }
                }
            }
        }
        return $data;
    }

    public function getType()
    {
        return 'collection';
    }
}