<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    public function testSetTranslation()
    {
        $typeMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $typeMock->expects($this->once())->method('setTranslation');
        $type = new Translation($typeMock, [], []);
        $type->setTranslation([], null, null, 'en', 'value');
    }

    public function testGetTranslation()
    {
        $typeMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $typeMock->expects($this->once())->method('getTranslation');
        $type = new Translation($typeMock, [], []);
        $type->getTranslation(null, null, 'en');
    }

    public function testGetValidationConstraint()
    {
        $typeMock = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();
        $typeMock->expects($this->once())->method('getValidationConstraints');
        $type = new Translation($typeMock, [], []);
        $type->getValidationConstraints( null, null, 'en');
    }
}
