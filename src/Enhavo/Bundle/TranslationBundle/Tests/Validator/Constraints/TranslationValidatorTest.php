<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Validator\Constraints;


use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\TranslationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

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
        $dependencies->context->method('getMetadata')->willReturn($this->getMockBuilder(MetadataInterface::class));

        $validator = $this->createInstance($dependencies);
        $validator->initialize($dependencies->context);
        $this->expectException(RuntimeException::class);
        $validator->validate(null, new Translation([]));
    }

    public function testValidate()
    {
        $dependencies = $this->createDependencies();
        $dependencies->context->method('getMetadata')->willReturn($dependencies->metadata);
        $dependencies->context->method('getObject')->willReturn(new \stdClass());
        $dependencies->metadata->method('getPropertyName')->willReturn('field');
        $dependencies->translationManager->method('getTranslations')->willReturn([
            'am' => 'Htzlprmpf',
            'af' => 'Null'
        ]);

        $dependencies->context->expects($this->exactly(5))->method('buildViolation')->willReturnCallback(function () {
            return $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
        });
        $dependencies->validator->method('validate')->willReturnCallback(function ($value, $constraint) {
            $this->assertEquals('constA', $constraint);
            $this->assertNotNull($value);
            return [$this->getMockBuilder(ConstraintViolationInterface::class)->getMock()];
        });

        $validator = $this->createInstance($dependencies);
        $validator->initialize($dependencies->context);


        $constraint = new Translation([
            'validateDefaultValue' => false,
            'constraints' => [
                'constA'
            ]
        ]);

        $validator->validate(null, $constraint);

        $constraint = new Translation([
            'validateDefaultValue' => true,
            'constraints' => [
                'constA'
            ]
        ]);

        $validator->validate('not null', $constraint);
    }
}

class TranslationValidatorTestDependencies
{
    /** @var TranslationManager|MockObject*/
    public $translationManager;

    /** @var ValidatorInterface|MockObject */
    public $validator;

    /** @var ExecutionContextInterface|MockObject */
    public $context;

    /** @var MetadataInterface|MockObject */
    public $metadata;
}
