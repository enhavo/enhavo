<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;


use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CollectionType extends AbstractIndexType
{
    function index($val, $options)
    {
        $model = $options['model'];
        $yamlFile = $options['yaml'];
        if(array_key_exists($model,$yamlFile )){
            $colProperties = $yamlFile[$model]['properties'];
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($val as $singleText){
                foreach($colProperties as $key => $value){
                    $this->indexEngine->switchToIndexingType($accessor->getValue($singleText, $key), $value, $options['dataSet']);
                }
            }
        }
    }

    /**
     * Returns a unique type name for this type
     *
     * @return string
     */
    public function getType()
    {
        return 'Collection';
    }

}