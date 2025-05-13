<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Validator\Constraints;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\TranslationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TranslationValidatorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new TranslationValidatorTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $dependencies->context = $this->getMockBuilder(ExecutionContextInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->metadata = $this->getMockBuilder(PropertyMetadataInterface::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        return new TranslationValidator(
            $dependencies->translationManager,
            $dependencies->validator
        );
    }

    public function testWrongMetadata()
    {
        $dependencies = $this->createDependencies();
        $dependencies->context->method('getMetadata')->willReturn($this->getMockBuilder(MetadataInterface::class)->getMock());

        $validator = $this->createInstance($dependencies);
        $validator->initialize($dependencies->context);
        $this->expectException(RuntimeException::class);
        $validator->validate(null, new Translation([]));
    }

    public function testValidate()
    {
        $constraint = $this->getMockBuilder(Constraint::class)->disableOriginalConstructor()->getMock();

        $dependencies = $this->createDependencies();
        $dependencies->context->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->context->method('getObject')->willReturn(new \stdClass());
        $dependencies->metadata->method('getPropertyName')->willReturn('field');
        $dependencies->translationManager->method('getTranslations')->willReturn([
            'am' => 'Htzlprmpf',
            'af' => 'Null',
        ]);

        $dependencies->validator->expects($this->exactly(2))->method('validate')->willReturnCallback(function ($value, $constraintArgument) use ($constraint) {
            $this->assertTrue($constraint === $constraintArgument);

            return $this->getMockBuilder(ConstraintViolationListInterface::class)->getMock();
        });

        $validator = $this->createInstance($dependencies);

        $validator->initialize($dependencies->context);

        $constraint = new Translation([
            'validateDefaultValue' => false,
            'constraints' => [
                $constraint,
            ],
        ]);

        $validator->validate(null, $constraint);
    }
}

class TranslationValidatorTestDependencies
{
    /** @var TranslationManager|MockObject */
    public $translationManager;

    /** @var ValidatorInterface|MockObject */
    public $validator;

    /** @var ExecutionContextInterface|MockObject */
    public $context;

    /** @var MetadataInterface|MockObject */
    public $metadata;
}
