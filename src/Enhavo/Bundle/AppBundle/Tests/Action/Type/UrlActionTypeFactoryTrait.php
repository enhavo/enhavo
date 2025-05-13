<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\UrlActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\RouterMock;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Routing\RouterInterface;

trait UrlActionTypeFactoryTrait
{
    public function createUrlActionType(UrlActionTypeDependencies $dependencies): UrlActionType
    {
        return new UrlActionType($dependencies->router, $dependencies->expressionLanguage);
    }

    public function createUrlActionTypeDependencies(): UrlActionTypeDependencies
    {
        $dependencies = new UrlActionTypeDependencies();
        $dependencies->router = new RouterMock();
        $dependencies->expressionLanguage = new ResourceExpressionLanguage();

        return $dependencies;
    }
}

class UrlActionTypeDependencies
{
    public RouterInterface|MockObject $router;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
