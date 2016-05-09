<?php
/**
 * PageRepository.php
 *
 * @since 08/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\PageBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function getEmptyWorkflowStatus()
    {
        $query = $this->createQueryBuilder('n');
        $query->where('n.workflow_status IS NULL');
        $test = $query->getQuery()->getResult();
        return $test;
    }
}