<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Enhavo\Bundle\ApiBundle\Data\Data;

class ResourceItem extends Data
{
    public function __construct(
        $data,
        private $resource,
    )
    {
        parent::__construct([
            'data' => $data
        ]);
    }

    public function getResource()
    {
        return $this->resource;
    }
}
