<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:30
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;

class ModelType extends AbstractIndexType
{
    function index($val, $options)
    {
        $model = $options['model'];
        $yamlFile = $options['yaml'];
        if(array_key_exists($model, $yamlFile)){
            $colProperties = $yamlFile[$model]['properties'];
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($colProperties as $key => $value){
                $currentText = $accessor->getValue($val, $key);
                $this->indexEngine->switchToIndexingType($currentText, $value, $options['dataSet']);
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
        return 'Model';
    }

}