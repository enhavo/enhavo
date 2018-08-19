<?php
/**
 * TranslationRouteManager.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Manager\RouteManager;
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
            'typeId' => $route->getTypeId(),
            'locale' => $locale
        ]);

        if($translationRoute === null) {
            $route = new Route();
            $route->setContent($routeable);

            $translationRoute = new TranslationRoute();
            $translationRoute->setLocale($locale);
            $translationRoute->setRoute($route);
            $this->em->persist($translationRoute);
        }

        /** @var Route $route */
        $route = $translationRoute->getRoute();
        $route->setStaticPrefix($staticPrefix);
        $translationRoute->setPath($staticPrefix);

        $this->em->flush();
        $this->routeManager->update($route);
        $translationRoute->setType($route->getType());
        $translationRoute->setTypeId($route->getTypeId());
        $this->em->flush();

        return $translationRoute;
    }

    public function getRoute(Routeable $routeable, $locale)
    {
        $route = $routeable->getRoute();
        if($route instanceof Route) {
            $translationRoute = $this->em->getRepository('EnhavoTranslationBundle:TranslationRoute')->findOneBy([
                'type' => $route->getType(),
                'typeId' => $route->getTypeId(),
                'locale' => $locale
            ]);
            if($translationRoute) {
                return $translationRoute->getRoute();
            }
            return $route;
        }
        return null;
    }
}