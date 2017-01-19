<?php
/**
 * FilterQuery.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;

class FilterQueryBuilder
{
    public function buildQueryFromRequestConfiguration(RequestConfiguration $requestConfiguration)
    {
        return new FilterQuery();
    }
}