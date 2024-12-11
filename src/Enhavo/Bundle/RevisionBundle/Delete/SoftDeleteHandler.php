<?php

namespace Enhavo\Bundle\RevisionBundle\Delete;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Delete\DeleteHandlerInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class SoftDeleteHandler implements DeleteHandlerInterface, ServiceSubscriberInterface
{
    private ContainerInterface $container;

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
    }

    public static function getSubscribedServices(): array
    {
        return [
            RevisionManager::class => RevisionManager::class,
        ];
    }

    public function delete(object $resource): void
    {
        if ($resource instanceof RevisionInterface) {
            $this->container->get(RevisionManager::class)->softDelete($resource);
        } else {
            $this->em->remove($resource);
            $this->em->flush();
        }
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
