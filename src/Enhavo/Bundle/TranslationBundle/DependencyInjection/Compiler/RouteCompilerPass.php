<?php
/**
 * RouteCompilerPass.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouteCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->overwriteRouteType($container);
        $this->overwriteSlugType($container);
        $this->overwriteRouteValidator($container);
        $this->overwriteRouteGuesser($container);
        $this->overwriteAutoGenerator($container);
        $this->overwriteRouteGuessGenerator($container);
    }

    protected function overwriteRouteType(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('enhavo_app.form.route');
        $definition->setClass('Enhavo\Bundle\TranslationBundle\Form\Type\RouteType');
        $definition->addArgument($container->getParameter('enhavo_translation.translate'));
    }

    protected function overwriteSlugType(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('enhavo_slug');
        $definition->setClass('Enhavo\Bundle\TranslationBundle\Form\Type\SlugType');
        $definition->addArgument($container->getParameter('enhavo_translation.translate'));
    }

    protected function overwriteRouteValidator(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('enhavo_app.validator.unique_url');
        $definition->setClass('Enhavo\Bundle\TranslationBundle\Validator\Constraints\RouteValidator');
    }

    protected function overwriteRouteGuesser(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('enhavo_app.route_guesser');
        $definition->setClass('Enhavo\Bundle\TranslationBundle\Route\RouteGuesser');
    }

    protected function overwriteAutoGenerator(ContainerBuilder $container)
    {
        if($container->getParameter('enhavo_translation.translate')) {
            $definition = $container->getDefinition('enhavo_app.route.auto_generator');
            $definition->setClass('Enhavo\Bundle\TranslationBundle\Route\AutoGenerator');
        }
    }

    protected function overwriteRouteGuessGenerator(ContainerBuilder $container)
    {
        if($container->getParameter('enhavo_translation.translate')) {
            $definition = $container->getDefinition('enhavo_app.route_guess_generator');
            $definition->setClass('Enhavo\Bundle\TranslationBundle\Route\RouteGuessGenerator');
            $definition->addArgument($container->getDefinition('enhavo_translation.translator'));
        }
    }
}