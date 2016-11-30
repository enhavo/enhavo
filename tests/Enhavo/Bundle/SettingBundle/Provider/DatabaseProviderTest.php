<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 29/09/16
 * Time: 11:35
 */

namespace Enhavo\Bundle\SettingBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Enhavo\Bundle\SettingBundle\Provider\DatabaseProvider;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Enhavo\Bundle\SettingBundle\Entity\Setting;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseProviderTest extends \PHPUnit_Framework_TestCase
{

    private function getKernelMock()
    {
        $mock = $this->getMockBuilder(KernelInterface::class)->getMock();
        return $mock;
    }

    private function getEntityManagerMock()
    {
        $repository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $repository->method('findOneBy')->willReturn(null);

        $mock = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $mock->method('getRepository')->willReturn($repository);

        return $mock;
    }

    private function getFactoryMock()
    {
        $mock = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $mock->method('createNew')->willReturnCallback(function() {
            return new Setting();
        });
        return $mock;
    }

    private function getFileSystemMock()
    {
        $mock = $this->getMockBuilder(Filesystem::class)->getMock();
        $mock->method('readFile')->willReturn(json_encode([
            'setting1' => [
                'type' => 'text',
                'value' => 'actual_value',
                'label' => 'actual_label',
                'translationDomain' => 'actual_translationDomain'
            ],
            'setting2' => [],
            'setting3' => [
                'type' => 'actual_type_set3'
            ],
            'setting4' => [
                'label' => 'actual_label_set4'
            ]
        ]));
        return $mock;
    }

    public function testInit()
    {
        $filesystem = $this->getFileSystemMock();
        $kernel = $this->getKernelMock();
        $em = $this->getEntityManagerMock();
        $factory = $this->getFactoryMock();

        $em->expects($this->any())->method('persist')->willReturnCallback(function (Setting $object){
            static::assertEquals('setting1', $object->getKey());
            static::assertEquals('text', $object->getType());
            static::assertEquals('actual_value', $object->getValue());
            static::assertEquals('actual_label', $object->getLabel());
            static::assertEquals('actual_translationDomain', $object->getTranslationDomain());
        });

        $databaseProvider = new DatabaseProvider($kernel, $em, $factory, $filesystem);
        $databaseProvider->init();

    }

    public function testGetSetting()
    {
        $filesystem = $this->getFileSystemMock();
        $kernel = $this->getKernelMock();
        $em = $this->getEntityManagerMock();
        $factory = $this->getFactoryMock();

        $persistSetting = null;
        $em->expects($this->any())->method('persist')->willReturnCallback(function (Setting $object) use(&$persistSetting) {
            $persistSetting = $object;
        });

        $databaseProvider = new DatabaseProvider($kernel, $em, $factory, $filesystem);
        $value = $databaseProvider->getSetting('setting1');
        static::assertEquals('actual_value', $value);
    }

    public function testCacheEntryNotValid()
    {
        $filesystem = $this->getFileSystemMock();
        $kernel = $this->getKernelMock();
        $em = $this->getEntityManagerMock();
        $factory = $this->getFactoryMock();

        $databaseProvider = new DatabaseProvider($kernel, $em, $factory, $filesystem);

        $setting = $databaseProvider->getSetting('setting2');
        static::assertNull($setting);
    }

    public function testKeyNotExistentInCache()
    {
        $filesystem = $this->getFileSystemMock();
        $kernel = $this->getKernelMock();
        $em = $this->getEntityManagerMock();
        $factory = $this->getFactoryMock();

        $databaseProvider = new DatabaseProvider($kernel, $em, $factory, $filesystem);

        $setting = $databaseProvider->getSetting('setting5');
        static::assertNull($setting);
    }
}