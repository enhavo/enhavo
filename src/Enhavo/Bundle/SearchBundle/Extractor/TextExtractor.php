<?php

namespace Enhavo\Bundle\SearchBundle\Extractor;

use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Enhavo\Bundle\SearchBundle\Index\IndexWalker;

/**
 * Extractor.php
 * Gets the raw data of a resource
 */
class TextExtractor
{

    public function __construct(SearchUtil $util, MetadataFactory $metadataFactory, ContainerInterface $container, IndexWalker $indexWalker)
    {
        $this->util = $util;
        $this->metadataFactory = $metadataFactory;
        $this->container = $container;
        $this->indexWalker = $indexWalker;
    }

    public function extract($resource)
    {
        if($this->container->getParameter('enhavo_search.search.search_engine') == 'enhavo_search_search_engine') {

            // for enhavo IndexEngine
            $resourceDataset = $this->util->getDataset($resource);
            return $resourceDataset->getRawdata();
        } else {

            // for elastic search
            $metadata = $this->metadataFactory->create($resource);
            $indexItems = $this->indexWalker->getIndexItems($resource, $metadata, array('rawData'));
            $text = '';
            foreach ($indexItems as $indexItem) {
                $text .= "\n ".$indexItem->getRawData();
            }
            return trim($text, "\n");
        }
    }
}