<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.06.16
 * Time: 12:48
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Elasticsearch;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\SearchBundle\Index\Type\PdfType;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

/*
 * This class does the indexing of the ElasticSearch
 */
class IndexElasticsearchEngine implements IndexEngineInterface
{
    protected $util;

    protected $index;

    protected $type;

    protected $pdfType;

    protected $indexWalker;

    public function __construct(SearchUtil $util, MetadataFactory $metadataFactory, IndexWalker $indexWalker)
    {
        $this->util = $util;
        $this->metadataFactory = $metadataFactory;
        $this->indexWalker = $indexWalker;
    }

    public function index($resource)
    {
        $client = Elasticsearch\ClientBuilder::create()->build();

        //get Entity and Bundle names
        $metadata = $this->metadataFactory->create($resource);
        $this->index = $metadata->getHumanizedBundleName();
        $this->type = strtolower($metadata->getEntityName());

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $resource->getID(),
            'body' => []
        ];

        //get items to index
        $indexItems = $this->indexWalker->getIndexItems($resource, $metadata, array('rawData', 'fieldName'));

        //set the information of the items to the body
        foreach ($indexItems as $indexItem) {
            $params = $this->addToBody($params, $indexItem->getFieldName(), $indexItem->getRawData());
        }

        //the client does the indexing in elasticsearch
        $client->index($params);
    }

    public function unindex($resource)
    {
        $client = Elasticsearch\ClientBuilder::create()->build();

        //get Entity and Bundle names
        $metadata = $this->metadataFactory->create($resource);
        $index = $metadata->getHumanizedBundleName();
        $type = strtolower($metadata->getEntityName());

        //get Index to unindex
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $resource->getID()
        ];

        //the client deletes the index
        $client->delete($params);
    }

    public function reindex(){}

    protected function addToBody($params, $field, $value)
    {
        //if the value is a pdf, get the content
        if($value instanceof FileInterface) {
            $value = $this->pdfType->getPdfContent($value);
        }

        //adds fields to params body
        if($value){
            $params['body'][$field] = $value;
        }
        return $params;
    }
}