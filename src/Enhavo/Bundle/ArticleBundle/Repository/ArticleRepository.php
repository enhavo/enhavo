<?php
/**
 * ArticleRepository.php
 *
 * @since 27/09/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\ArticleBundle\Repository;

use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;

class ArticleRepository extends ContentRepository
{
    public function getEmptyWorkflowStatus()
    {
        $query = $this->createQueryBuilder('n');
        $query->where('n.workflow_status IS NULL');
        return $query->getQuery()->getResult();
    }
}
