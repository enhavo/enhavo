<?php
/**
 * EntityRepositoryInterface.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface EntityRepositoryInterface extends RepositoryInterface
{
    /**
     * @param FilterQuery $filterQuery
     * @return mixed
     */
    public function filter(FilterQuery $filterQuery);

    /**
     * @param $property
     * @param \DateTime $from
     * @param \DateTime $to
     * @param array $criteria
     * @param array $orderBy
     * @return mixed
     */
    public function findBetween($property, \DateTime $from, \DateTime $to, $criteria = [], $orderBy = []);
}
