<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\ResourceBundle\Column\Type\BaseColumnType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\TranslatorMock;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Translation\TranslatorInterface;

trait BaseColumnTypeFactoryTrait
{
    public function createBaseColumnTypeDependencies(): BaseColumnTypeDependencies
    {
        $dependencies = new BaseColumnTypeDependencies();
        $dependencies->translator = new TranslatorMock('.trans');
        $dependencies->expressionLanguage = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createBaseColumnType(BaseColumnTypeDependencies $dependencies): BaseColumnType
    {
        $instance = new BaseColumnType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
        );

        return $instance;
    }
}

class BaseColumnTypeDependencies
{
    public TranslatorInterface|MockObject $translator;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
