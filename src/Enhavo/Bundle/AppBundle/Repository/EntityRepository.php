<?php
/**
 * EntityRepository.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as SyliusEntityRepository;

class EntityRepository extends SyliusEntityRepository
{
    public function addAtFirst($resource, $property = 'position')
    {

    }

    public function addAtLast($resource, $property = 'position')
    {

    }
}