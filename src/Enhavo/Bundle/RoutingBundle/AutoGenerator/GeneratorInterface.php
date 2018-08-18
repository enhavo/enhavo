<?php
/**
 * GeneratorInterface.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator;

use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface GeneratorInterface extends TypeInterface
{
    /**
     * @param RouteInterface $route
     * @param $options
     * @return void
     */
    public function generate(RouteInterface $route, $options);

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}