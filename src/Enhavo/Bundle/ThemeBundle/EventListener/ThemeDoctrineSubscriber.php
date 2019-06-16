<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-14
 * Time: 15:05
 */

namespace Enhavo\Bundle\ThemeBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\ThemeBundle\Model\Entity\Theme;
use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ThemeDoctrineSubscriber implements EventSubscriber
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
        if($entity instanceof Theme) {
            $theme = $this->getThemeManager()->getTheme($entity->getKey());
            $entity->setTheme($theme);
        }
    }

    /**
     * @return ThemeManager
     */
    private function getThemeManager()
    {
        return $this->container->get('enhavo_theme.theme.manager');
    }
}
