<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 15:05
 */

namespace Enhavo\Bundle\TemplateBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Template\TemplateManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TemplateDoctrineSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof Template) {
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
