<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\Type\BaseActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Translation\TranslatorInterface;

trait BaseActionTypeFactoryTrait
{
    public function createBaseActionType(BaseActionTypeDependencies $dependencies): BaseActionType
    {
        return new BaseActionType($dependencies->translator, $dependencies->expressionLanguage);
    }

    public function createBaseActionTypeDependencies(): BaseActionTypeDependencies
    {
        $dependencies = new BaseActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($label) {
            return $label.'.translated';
        });
        $dependencies->expressionLanguage = new ResourceExpressionLanguage();

        return $dependencies;
    }
}

class BaseActionTypeDependencies
{
    public TranslatorInterface|MockObject $translator;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
