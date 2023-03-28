<?php

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

class ElasticSearchDocumentIdGenerator
{
    public function generateDocumentId($classname, $entityId)
    {
        return sha1($entityId . $classname);
    }
}
