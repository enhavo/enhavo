<?php

namespace Enhavo\Bundle\NewsletterBundle;

use Enhavo\Bundle\NewsletterBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Enhavo\Component\Type\TypeCompilerPass;

class EnhavoNewsletterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new TypeCompilerPass('Storage', 'enhavo_newsletter.storage', Storage::class)
        );
        $container->addCompilerPass(
            new TypeCompilerPass('Strategy', 'enhavo_newsletter.strategy', Strategy::class)
        );
        $container->addCompilerPass(new ProviderCompilerPass());
    }

    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }
}
