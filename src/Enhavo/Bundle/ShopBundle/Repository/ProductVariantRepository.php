<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 16.09.20
 * Time: 11:42
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class ProductVariantRepository extends ContentRepository
{
    public function findByProductId(FilterQuery $filterQuery, $productId)
    {
        $filterQuery->addWhere('product',FilterQuery::OPERATOR_EQUALS, $productId);
        return $this->filter($filterQuery);
    }
}

