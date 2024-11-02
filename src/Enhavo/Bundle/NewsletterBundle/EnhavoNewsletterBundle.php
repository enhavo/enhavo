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
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new TypeCompilerPass('NewsletterStorage', 'enhavo_newsletter.storage', Storage::class)
        );
        $container->addCompilerPass(
            new TypeCompilerPass('NewsletterStrategy', 'enhavo_newsletter.strategy', Strategy::class)
        );
        $container->addCompilerPass(new ProviderCompilerPass());
    }

}
