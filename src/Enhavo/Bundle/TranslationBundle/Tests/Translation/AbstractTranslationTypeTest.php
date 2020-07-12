<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use PHPUnit\Framework\TestCase;

class AbstractTranslationTypeTest extends TestCase
{
    public function testSetTranslation()
    {
        $parentMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $parentMock->expects($this->once())->method('setTranslation');
        $type = new ConcreteTranslationType();
        $type->setParent($parentMock);

        $type->setTranslation([], null, null, 'en', 'value');
    }

    public function testGetTranslation()
    {
        $parentMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $parentMock->expects($this->once())->method('getTranslation');
        $type = new ConcreteTranslationType();
        $type->setParent($parentMock);

        $type->getTranslation([], null, null, 'en');
    }

    public function testGetValidationConstraint()
    {
        $parentMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $parentMock->expects($this->once())->method('getValidationConstraints');
        $type = new ConcreteTranslationType();
        $type->setParent($parentMock);

        $type->getValidationConstraints([], null, null, 'en');
    }

    public function testGetParentType()
    {
        $this->assertEquals(TranslationType::class, ConcreteTranslationType::getParentType());
    }
}

class ConcreteTranslationType extends AbstractTranslationType
{

}
