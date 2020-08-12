<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    private function createDependencies()
    {
        /** @var TranslationTypeInterface|MockObject $translationType */
        $translationType = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();

        return $translationType;
    }

    public function testSetTranslation()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('setTranslation');
        $type = new Translation($typeMock, [], []);
        $type->setTranslation([], 'field', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('getTranslation');
        $type = new Translation($typeMock, [], []);
        $type->getTranslation(null, 'field', 'en');
    }

    public function testGetValidationConstraint()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('getValidationConstraints');
        $type = new Translation($typeMock, [], []);
        $type->getValidationConstraints( null, 'field', 'en');
    }
}
