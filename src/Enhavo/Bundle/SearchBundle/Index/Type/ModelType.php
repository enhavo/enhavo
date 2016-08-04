<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:30
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\SearchBundle\Index\AbstractIndexType;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/*
 * Prepares fields of type model for indexing
 */
class ModelType extends AbstractIndexType
{
    protected $metadataFactory;

    public function __construct(SearchUtil $util, ContainerInterface $container, MetadataFactory $metadataFactory)
    {
        parent::__construct($util, $container);
        $this->metadataFactory = $metadataFactory;
    }

    function index($val, $options, $properties = null)
    {
        //get the metadata of the given model
        $metaData = $this->metadataFactory->create($val);

        //get the IndexWalker
        $indexWalker = $this->getIndexWalker();

        //return IndexItems of indexWalker; put the model and the metadata into the funktion of the IndexWalker
        return $indexWalker->getIndexItems($val, $metaData, $properties);
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