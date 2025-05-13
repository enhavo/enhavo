<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Tests\Fixtures;

class AttributeTestClass
{
    #[AttributeClass('myType', ['option1' => 'valueOption1'])]
    private string $property = 'Test';
}
