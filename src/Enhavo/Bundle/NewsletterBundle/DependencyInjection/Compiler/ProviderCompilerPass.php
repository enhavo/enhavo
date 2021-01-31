<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-12-14
 * Time: 14:27
 */

namespace Enhavo\Bundle\NewsletterBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases([
            'enhavo_newsletter.newsletter.provider' => $container->getParameter('enhavo_newsletter.newsletter.provider')
        ]);
    }
}
