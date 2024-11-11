<?php

namespace Enhavo\Bundle\ResourceBundle\Delete;

use Doctrine\ORM\EntityManagerInterface;

class DoctrineDeleteHandler implements DeleteHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    public function delete(object $resource): void
    {
        $this->em->remove($resource);
        $this->em->flush();
    }
}
