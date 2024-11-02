<?php

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
