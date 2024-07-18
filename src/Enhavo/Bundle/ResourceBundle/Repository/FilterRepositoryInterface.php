<?php
/**
 * EntityRepositoryInterface.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;

interface FilterRepositoryInterface
{
    public function filter(FilterQuery $filterQuery);
}
