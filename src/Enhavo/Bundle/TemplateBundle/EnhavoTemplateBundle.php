<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.05.19
 * Time: 18:23
 */

namespace Enhavo\Bundle\TemplateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTemplateBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        #$container->addCompilerPass(new SyliusCompilerPass());
        #$container->addCompilerPass(new ConfigCompilerPass());
    }
}
