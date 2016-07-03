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
    protected $metadataFactory;

    public function __construct(SearchUtil $util, ContainerInterface $container, MetadataFactory $metadataFactory)
    {
        parent::__construct($util, $container);
        $this->metadataFactory = $metadataFactory;
    }

    function index($val, $options, $properties = null)
    {
        $indexItems = [];
        if($val != null){
            foreach ($val as $model) {
                $metaData = $this->metadataFactory->create($model);
                $indexWalker = $this->getIndexWalker();
                $newIndexItems = $indexWalker->getIndexItems($model, $metaData, $properties);
                $indexItems = array_merge($indexItems, $newIndexItems);
            }
        }
        return $indexItems;
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