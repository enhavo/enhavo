<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Delete\DeleteHandlerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DeleteHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $deleteHandler = $container->getParameter('enhavo_resource.delete.handler');

        if (!$container->has($deleteHandler)) {
            throw new \InvalidArgumentException(sprintf('Resource delete handler "%s" must be registered service.', $deleteHandler));
        }

        $container->setAlias(DeleteHandlerInterface::class, $deleteHandler);
    }
}
