<?php


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

    public function testDeleteMultipleProperties()
    {
        $map = $this->createInstance();

        $entity = new \stdClass();
        $map->store($entity, 'propertyA', 'fr', 'valueA-fr');
        $map->store($entity, 'propertyA', 'de', 'valueA-de');
        $map->store($entity, 'propertyB', 'fr', 'valueB-fr');

        $map->delete($entity, 'propertyA', 'fr');
        $this->assertNull($map->load($entity, 'propertyA', 'fr'));
        $this->assertEquals('valueA-de', $map->load($entity, 'propertyA', 'de'));
        $this->assertEquals('valueB-fr', $map->load($entity, 'propertyB', 'fr'));
        $this->assertTrue($map->exists($entity));

        $map->delete($entity, 'propertyA', 'de');
        $this->assertNull($map->load($entity, 'propertyA', 'de'));
        $this->assertEquals('valueB-fr', $map->load($entity, 'propertyB', 'fr'));
        $this->assertTrue($map->exists($entity));

        $map->delete($entity, 'propertyB', 'fr');
        $this->assertNull($map->load($entity, 'propertyB', 'fr'));
        $this->assertFalse($map->exists($entity));
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

