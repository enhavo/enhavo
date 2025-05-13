<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

use PHPUnit\Framework\TestCase;

class PhpClassPropertyTest extends TestCase
{
    public function testGetAttributes()
    {
        $property = new PhpClassProperty('text', 'private', [
            'attributes' => [
                [
                    'class' => 'Duplicate',
                    'type' => 'property',
                    'options' => "['groups' => ['revision','restore']]",
                ],
                [
                    'class' => 'Duplicate',
                    'type' => 'property',
                    'options' => "['groups' => ['revision', 'restore']]",
                ],
                [
                    'class' => 'Duplicate',
                    'type' => 'model',
                    'options' => "['groups' => ['duplicate']]",
                ],
                [
                    'class' => 'Duplicate',
                    'type' => 'model',
                    'options' => "['groups' => ['revision', 'restore']]",
                ],
                [
                    'class' => 'Index',
                    'type' => 'html',
                ],
            ],
        ]);

        $attributes = $property->getAttributes();

        $this->assertCount(4, $attributes);
        $this->assertEquals([
            [
                'class' => 'Duplicate',
                'type' => 'property',
                'options' => "['groups' => ['revision', 'restore']]",
            ],
            [
                'class' => 'Duplicate',
                'type' => 'model',
                'options' => "['groups' => ['duplicate']]",
            ],
            [
                'class' => 'Duplicate',
                'type' => 'model',
                'options' => "['groups' => ['revision', 'restore']]",
            ],
            [
                'class' => 'Index',
                'type' => 'html',
            ],
        ], $attributes);
    }
}
