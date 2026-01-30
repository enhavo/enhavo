<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle;

use Enhavo\Bundle\AppBundle\Menu\Menu;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarWidget;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\LocaleResolverCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\RouteCollectorCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplateExpressionLanguageCompilerPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplateResolverPass;
use Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler\TranslationDumperCompilerPass;
use Enhavo\Bundle\FrameworkBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverAwareInterface;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverInterface;
use Enhavo\Bundle\FrameworkBundle\Vue\RouteProvider\RouteProvider;
use Enhavo\Bundle\FrameworkBundle\Vue\RouteProvider\VueRouteProviderTypeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoAppBundle extends Bundle
{
    public const VERSION = '0.15';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('ToolbarWidget', 'enhavo_app.toolbar_widget', ToolbarWidget::class)
        );

        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Menu', 'enhavo_app.menu', Menu::class)
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_app.widget_collector', 'enhavo.widget')
        );

    }
}
