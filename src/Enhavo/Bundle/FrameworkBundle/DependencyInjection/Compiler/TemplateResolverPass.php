<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolver;
use Enhavo\Bundle\FrameworkBundle\Template\TemplateResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author gseidel
 */
class TemplateResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setAlias(TemplateResolverInterface::class, TemplateResolver::class);
    }
}
