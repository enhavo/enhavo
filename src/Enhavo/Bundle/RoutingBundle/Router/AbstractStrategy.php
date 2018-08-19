<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.08.18
 * Time: 17:04
 */

namespace Enhavo\Bundle\RoutingBundle\Router;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractStrategy extends AbstractType implements StrategyInterface
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([]);
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->container->get('router');
    }
}