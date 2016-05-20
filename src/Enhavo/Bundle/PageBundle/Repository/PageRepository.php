<?php
/**
 * PageRepository.php
 *
 * @since 08/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\PageBundle\Repository;

use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class PageRepository extends ContentRepository
{
    public function getEmptyWorkflowStatus()
    {
        $query = $this->createQueryBuilder('n');
        $query->where('n.workflow_status IS NULL');
        $test = $query->getQuery()->getResult();
        return $test;
    }
}