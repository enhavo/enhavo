<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SearchEngineCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $dsn = $container->getParameter('enhavo_search.search.dsn');
        $dsn = $container->resolveEnvPlaceholders($dsn, true);

        $found = false;
        foreach ($container->findTaggedServiceIds('enhavo_search.engine') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();
            if (call_user_func([$class, 'supports'], $dsn)) {
                $container->addAliases([
                    'enhavo_search.search.engine' => $id,
                    SearchEngineInterface::class => $id,
                ]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception(sprintf('Can\'t find search engine with dsn "%s"', $dsn));
        }
    }
}
