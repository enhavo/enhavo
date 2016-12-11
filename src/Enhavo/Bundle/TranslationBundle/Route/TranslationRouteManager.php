<?php
/**
 * TranslationRouteManager.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Route\RouteManager;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;

class TranslationRouteManager
{
    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(RouteManager $routeManager, EntityManagerInterface $em)
    {
        $this->routeManager = $routeManager;
        $this->em = $em;
    }

    public function updateTranslationRoute(Routeable $routeable, $locale, $staticPrefix)
    {
        $route = $routeable->getRoute();
        $return = null;
        if(!$route instanceof Route) {
            return null;
        }

        $translationRoute = $this->em->getRepository('EnhavoTranslationBundle:TranslationRoute')->findOneBy([
            'type' => $route->getType(),
            'typeId' => $route->getTypeId()
        ]);

        if($translationRoute === null) {
            $route = new Route();
            $route->setContent($routeable);
            $this->routeManager->update($route);

            $translationRoute = new TranslationRoute();
            $translationRoute->setLocale($locale);
            $translationRoute->setRoute($route);
        }

        /** @var Route $route */
        $route = $translationRoute->getRoute();
        $route->setStaticPrefix($staticPrefix);
        $translationRoute->setPath($staticPrefix);

        return $translationRoute;
    }
}