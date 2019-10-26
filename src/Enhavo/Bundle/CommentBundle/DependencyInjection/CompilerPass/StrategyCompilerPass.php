<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 18:37
 */

namespace Enhavo\Bundle\CommentBundle\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->addAliases([
            'enhavo_comment.publish_strategy' => $container->getParameter('enhavo_comment.publish_strategy.strategy')
        ]);
    }
}
