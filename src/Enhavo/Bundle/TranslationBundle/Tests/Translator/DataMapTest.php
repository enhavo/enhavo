<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translator;

use Enhavo\Bundle\TranslationBundle\Translator\DataMap;
use PHPUnit\Framework\TestCase;

class DataMapTest extends TestCase
{
    private function createInstance()
    {
        return new DataMap();
    }

    public function testStoreRestore()
    {
        $map = $this->createInstance();

        $entity = new \stdClass();
        $entity->propertyA = 'testPropValue';
        $map->store($entity, 'propertyA', 'fr', 'testPropValueFr');
        $data = $map->load($entity, 'propertyA', 'fr');
        $this->assertEquals('testPropValueFr', $data);
        $map->store($entity, 'propertyA', 'fr', 'testPropValueFr2');
        $data = $map->load($entity, 'propertyA', 'fr');
        $this->assertEquals('testPropValueFr2', $data);
    }

    public function testNotFound()
    {
        $map = $this->createInstance();

        $entity = new \stdClass();
        $entity->propertyA = 'testPropValue';
        $map->store($entity, 'propertyA', 'fr', 'fr-value');
        $data = $map->load($entity, 'propertyA', 'de');

        $this->assertNull($data);
    }
}
