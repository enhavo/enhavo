<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AutoGenerator implements GeneratorInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    protected $autoGeneratorConfig;

    /**
     * @var RouteManager
     */
    protected $routeManager;

    /**
     * @var RouteManager
     */
    protected $em;

    public function __construct($autoGeneratorConfig, RouteManager $routeManager, EntityManagerInterface $em)
    {
        $this->autoGeneratorConfig = $autoGeneratorConfig;
        $this->routeManager = $routeManager;
        $this->em = $em;
    }

    public function generate(Routeable $routeable)
    {
        $className = get_class($routeable);
        if($routeable instanceof Proxy) {
            $className = get_parent_class($routeable);
        }

        foreach($this->autoGeneratorConfig as $config) {
            if($config['model'] == $className) {
                /** @var GeneratorInterface $generator */
                $generator = $this->container->get($config['generator']);
                $this->updateRoute($generator, $routeable);
            }
        }
    }

    protected function updateRoute(GeneratorInterface $generator, Routeable $routeable)
    {
        $staticPrefix = $generator->generate($routeable);
        $route = $routeable->getRoute();
        if($route instanceof Route) {
            $route->setStaticPrefix($staticPrefix);
        } else {
            $this->routeManager->createRoute($routeable, $staticPrefix);
        }

        $this->em->flush();
        $this->routeManager->update($route);
        $this->em->flush();
    }
}