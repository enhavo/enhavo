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

use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\TranslationValidator;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    private function createDependencies(array $options = [])
    {
        return array_merge(['constraints' => []], $options);
    }

    private function createInstance($options)
    {
        return new Translation($options);
    }

    public function testValidatedBy()
    {
        $this->assertEquals(TranslationValidator::class, $this->createInstance($this->createDependencies())->validatedBy());
    }

    public function testGetTargets()
    {
        $this->assertEquals(Translation::PROPERTY_CONSTRAINT, $this->createInstance($this->createDependencies())->getTargets());
    }
}
