<?php

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

