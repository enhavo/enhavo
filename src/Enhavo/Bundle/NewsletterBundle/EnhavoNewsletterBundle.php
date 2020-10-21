<?php

namespace Enhavo\Bundle\NewsletterBundle;

use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoNewsletterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Storage', 'enhavo_newsletter.storage', Storage::class)
        );
        $container->addCompilerPass(
            new \Enhavo\Component\Type\TypeCompilerPass('Strategy', 'enhavo_newsletter.strategy', Strategy::class)
        );

    }

    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

}
