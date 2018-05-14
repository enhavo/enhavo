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

class TextIndexer extends AbstractType implements IndexerInterface
{
    public function getIndexes($value, array $options = [])
    {
        if($value) {
            $index = new Index();
            $index->setWeight($this->getOption('weight', $options, 1));
            $index->setValue(trim($value));
            return [$index];
        }
        return [];
    }

    public function getType()
    {
        return 'text';
    }
}