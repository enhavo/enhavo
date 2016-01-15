<?php

namespace Enhavo\Bundle\MediaBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\Container;

class LifecycleListener
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function collectGarbage()
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $garbage = $entityManager->getRepository('EnhavoMediaBundle:File')->findGarbage();
        if ($garbage) {
            foreach($garbage as $file) {
                $entityManager->remove($file);
            }
            $entityManager->flush();
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof File) {
            return;
        }

        $this->container->get('enhavo_media.file_service')->deleteFile($entity);
    }
}
