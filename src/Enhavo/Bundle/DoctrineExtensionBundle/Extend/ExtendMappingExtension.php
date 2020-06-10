<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 21:30
 */

namespace Enhavo\Component\DoctrineExtension\Extend;

use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Component\DoctrineExtension\Mapping\MappingExtensionInterface;
use Doctrine\Persistence\Mapping\MappingException;

class ExtendMappingExtension implements MappingExtensionInterface
{
    /** @var string[] */
    private $className;

    /** @var string[] */
    private $classMap = [];

    /** @var MappingDriver */
    private $driver;

    public function loadDriver(MappingDriver $driver)
    {
        $className = $driver->getAllClassNames();
    }

    public function loadClass($className, MappingDriver $driver)
    {

        if(isset($this->classMap[$className])) {
           return;
        }
        $this->classMap[$className] = true;

        if ($driver instanceof XmlDriver) {
            $this->loadXml($className, $driver->getElement($className));
        } elseif ($driver instanceof YamlDriver) {
            $this->loadYaml($className, $driver->getElement($className));
        }
    }

    private function loadXml($className, $mappingInformation)
    {

    }

    private function loadYaml($className, $mappingInformation)
    {

    }
}
