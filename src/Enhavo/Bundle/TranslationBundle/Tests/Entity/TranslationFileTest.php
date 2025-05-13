<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Entity;

use Enhavo\Bundle\TranslationBundle\Entity\TranslationFile;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use PHPUnit\Framework\TestCase;

class TranslationFileTest extends TestCase
{
    public function testGettersSetters()
    {
        $translatable = new TranslatableMock();
        $translation = new TranslationFile();
        $translation->setRefId(1);
        $translation->setClass(TranslatableMock::class);
        $translation->setLocale('_de');
        $translation->setProperty('_prop');
        $translation->setObject($translatable);
        $this->assertEquals(1, $translation->getRefId());
        $this->assertEquals(TranslatableMock::class, $translation->getClass());
        $this->assertEquals('_de', $translation->getLocale());
        $this->assertEquals('_prop', $translation->getProperty());
        $this->assertEquals($translatable, $translation->getObject());
    }
}
