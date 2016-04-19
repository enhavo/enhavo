<?php

namespace Enhavo\Bundle\NewsletterBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class NewsletterRepository extends EntityRepository
{
    public function getWorkflowStatusNull()
    {
        $query = $this->createQueryBuilder('n');
        $query->where('n.workflow_status IS NULL');
        return $query->getQuery()->getResult();
    }
}