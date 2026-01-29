<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Template\TemplateManager;
use Psr\Container\ContainerInterface;

class TemplateDoctrineSubscriber implements EventSubscriber
{
    private ?ContainerInterface $container = null;

    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
        ];
    }

    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Template) {
            $template = $this->getTemplateManager()->getTemplate($entity->getCode());
            $entity->setTemplate($template);
        }
    }

    /**
     * @return TemplateManager
     */
    private function getTemplateManager()
    {
        return $this->container->get(TemplateManager::class);
    }
}
