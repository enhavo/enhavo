<?php

namespace Enhavo\Bundle\RevisionBundle;

use Enhavo\Bundle\RevisionBundle\Restore\Restore;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoRevisionBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TypeCompilerPass('Restore', 'enhavo_revision.restore', Restore::class));
    }
}
