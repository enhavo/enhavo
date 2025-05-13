<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle;

use Enhavo\Bundle\NewsletterBundle\DependencyInjection\Compiler\ProviderCompilerPass;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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
