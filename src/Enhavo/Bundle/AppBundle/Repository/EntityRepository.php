<?php
/**
 * EntityRepository.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Repository;

use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as SyliusEntityRepository;

class EntityRepository extends SyliusEntityRepository
{
    public function filter(FilterQuery $query)
    {
        return $this->createPaginator([]);
    }
}