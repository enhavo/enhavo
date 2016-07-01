<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class CollectionType extends AbstractIndexType
{

    function index($val, $options)
    {
        $indexItem = [];
        if($val != null){
            foreach ($val as $model) {
                $metaData = $this->metadataFactory->create($model);

                $indexWalker = $this->getIndexWalker();
                $newIndexItem = $indexWalker->getIndexItems($model, $metaData);
                if(empty($indexItem)){
                    $indexItem = $newIndexItem;
                } else {
                    $indexItem->setData($indexItem->getData().$newIndexItem->getData());
                    $indexItem->setScoredWords((array_merge($indexItem->getScoredWords(), $newIndexItem->getScoredWords())));
                }
            }
        }
        return $indexItem;
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