<?php
/**
 * DoctrineRouteContentSubscriber.php
 *
 * @since 19/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\TranslationBundle\AutoGenerator\LocaleAutoGenerator;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RouteableListener implements EventSubscriberInterface
{
    /**
     * @var LocaleAutoGenerator
     */
    private $localeAutoGenerator;

    public function __construct(LocaleAutoGenerator $localeAutoGenerator)
    {
        $this->localeAutoGenerator = $localeAutoGenerator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'enhavo_app.pre_create' => 'preSave',
            'enhavo_app.pre_update' => 'preSave'
        );
    }

    public function preSave(ResourceControllerEvent $event)
    {
        $resource = $event->getSubject();
        if($resource instanceof Routeable || $resource instanceof Slugable) {
            $this->localeAutoGenerator->generate($resource);
        }
    }
}
