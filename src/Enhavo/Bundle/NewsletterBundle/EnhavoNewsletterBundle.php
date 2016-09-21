<?php

namespace Enhavo\Bundle\NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Type\TypeCompilerPass;

class EnhavoNewsletterBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array('doctrine/orm');
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_newsletter.strategy_collector', 'enhavo.newsletter_strategy')
        );

        $container->addCompilerPass(
            new TypeCompilerPass('enhavo_newsletter.storage_collector', 'enhavo.newsletter_storage')
        );
    }
}
