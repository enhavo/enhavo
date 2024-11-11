<?php

namespace Enhavo\Bundle\RevisionBundle\Delete;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Delete\DeleteHandlerInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;

class SoftDeleteHandler implements DeleteHandlerInterface
{
    private RevisionManager $revisionManager;

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function delete(object $resource): void
    {
        if ($resource instanceof RevisionInterface) {
            $this->revisionManager->softDelete($resource);
        } else {
            $this->em->remove($resource);
            $this->em->flush();
        }
    }

    public function setRevisionManager(RevisionManager $revisionManager): void
    {
        $this->revisionManager = $revisionManager;
    }
}
