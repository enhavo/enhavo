<?php
/**
 * EntityRepository.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as SyliusEntityRepository;

class EntityRepository extends SyliusEntityRepository implements EntityRepositoryInterface
{
    use EntityRepositoryTrait;
}
