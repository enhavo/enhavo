<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Enhavo\Bundle\ApiBundle\Data\Data;

class ResourceItem extends Data
{
    public function __construct(
        $data,
        private $resource,
    ) {
        parent::__construct([
            'data' => $data,
        ]);
    }

    public function getResource()
    {
        return $this->resource;
    }
}
