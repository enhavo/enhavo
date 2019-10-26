<?php

namespace Enhavo\Bundle\CommentBundle;

use Enhavo\Bundle\CommentBundle\DependencyInjection\CompilerPass\StrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoCommentBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new StrategyCompilerPass());
    }
}
