<?php
/**
 * EntityRepository.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Repository;

class EntityRepository extends \Doctrine\ORM\EntityRepository implements FilterRepositoryInterface
{
    use FilterRepositoryTrait;
    use EntityRepositoryTrait;
}
