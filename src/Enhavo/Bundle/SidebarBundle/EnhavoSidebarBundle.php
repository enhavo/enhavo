<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.05.19
 * Time: 18:23
 */

namespace Enhavo\Bundle\SidebarBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoSidebarBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
