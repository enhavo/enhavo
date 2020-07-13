<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.09.18
 * Time: 01:56
 */

namespace Bundle\MultiTenancyBundle\Factory;

use Bundle\MultiTenancyBundle\Model\MultiTenancyConfiguration;
use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ConfigurationFactory implements ConfigurationFactoryInterface
{
    public function createNew()
    {
        return new MultiTenancyConfiguration();
    }

    public function create($config)
    {
        $configuration = $this->createNew();
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach($config as $property => $value) {
            try {
                $propertyAccessor->setValue($configuration, $property, $value);
            } catch (PropertyNotExistsException $e) {
                // do nothing
            }
        }

        return $configuration;
    }
}
