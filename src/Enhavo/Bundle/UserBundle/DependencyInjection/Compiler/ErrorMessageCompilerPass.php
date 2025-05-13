<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\UserBundle\Security\Authentication\AuthenticationError;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErrorMessageCompilerPass implements CompilerPassInterface
{
    public const TAG = 'enhavo_user.error_message';

    public function process(ContainerBuilder $container)
    {
        $targetService = $container->getDefinition(AuthenticationError::class);
        $definitions = $container->findTaggedServiceIds(self::TAG);

        foreach ($definitions as $id => $values) {
            $priority = 10;
            foreach ($values as $valueItem) {
                if (isset($valueItem['priority'])) {
                    $priority = $valueItem['priority'];
                    break;
                }
            }

            $definition = $container->getDefinition($id);
            $targetService->addMethodCall('addErrorMessage', [$definition, $priority]);
        }
    }
}
