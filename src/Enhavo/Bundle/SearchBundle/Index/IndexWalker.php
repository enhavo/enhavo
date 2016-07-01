<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 29.06.16
 * Time: 16:59
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Symfony\Component\PropertyAccess\PropertyAccess;


class IndexWalker
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    public function getIndexItems($resource, Metadata $metadata)
    {
        $indexItem = [];
        $accessor = PropertyAccess::createPropertyAccessor();

        //walk over each field of the property that should get indexed
        foreach($metadata->getProperties() as $propertyNode) {

            //get the right indexType
            $type = $this->collector->getType($propertyNode->getType());
            $newIndexItem = $type->index($accessor->getValue($resource, $propertyNode->getProperty()), $propertyNode->getOptions());
            if($newIndexItem){
                if(empty($indexItem)){
                    $indexItem = $newIndexItem;
                } else {
                    $indexItem->setRawData($indexItem->getRawData()."\n ".$newIndexItem->getRawData());
                    $indexItem->setData($indexItem->getData().$newIndexItem->getData());
                    $indexItem->setScoredWords(array_merge($indexItem->getScoredWords(), $newIndexItem->getScoredWords()));
                }
            }
        }
        return $indexItem;
    }
}