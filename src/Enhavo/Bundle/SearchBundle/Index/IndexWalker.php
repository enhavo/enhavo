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

/*
 * The IndexWalker gets all the IndexItems of a resource
 */
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

    public function getIndexItems($resource, Metadata $metadata, $properties = null)
    {
        $indexItems = [];
        $accessor = PropertyAccess::createPropertyAccessor();

        //walk over each field of the property that should get indexed
        foreach($metadata->getProperties() as $propertyNode) {

            //get the right indexType
            $type = $this->collector->getType($propertyNode->getType());

            //get the IndexItems of the right IndexType
            $newIndexItems = $type->index($accessor->getValue($resource, $propertyNode->getProperty()), $propertyNode->getOptions(), $properties);
            if($newIndexItems){
                foreach($newIndexItems as $indexItem){

                    //set fieldname
                    if((empty($properties) || in_array('fieldName', $properties)) && $indexItem->getFieldName() == null){
                        $indexItem->setFieldName($metadata->getHumanizedBundleName().'_'.strtolower($metadata->getEntityName().'_'.strtolower($propertyNode->getProperty())));
                    }
                    $indexItems[] = $indexItem;
                }
            }
        }
        return $indexItems;
    }
}