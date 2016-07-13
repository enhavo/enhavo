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

/*
 * Prepares fields of type collection for indexing
 */
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

        //check if there are items in the collection
        if($val != null){

            //if there are items walk over each of it
            foreach ($val as $model) {
                
                //get the metadata to every item in the collection
                $metaData = $this->metadataFactory->create($model);

                //get the IndexWalker
                $indexWalker = $this->getIndexWalker();

                //get IndexItems of indexWalker; put the current Item and the metadata into the funktion of the IndexWalker
                $newIndexItems = $indexWalker->getIndexItems($model, $metaData, $properties);

                //merge the IndexItems of the collection
                $indexItems = array_merge($indexItems, $newIndexItems);
            }
        }

        //return the IndexItems
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