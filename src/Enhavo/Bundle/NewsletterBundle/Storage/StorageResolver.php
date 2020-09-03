<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 03.12.16
 * Time: 17:24
 */

namespace Enhavo\Bundle\NewsletterBundle\Storage;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class StorageResolver
{
    /**
     * @var array
     */
    private $formTypes;

    /**
     * @var TypeCollector
     */
    private $storageTypeCollector;

    /**
     * @var StorageTypeInterface
     */
    private $defaultStorage;

    /**
     * StorageResolver constructor.
     *
     * @param $formTypes
     * @param $storageTypeCollector
     * @param $defaultStorage
     */
    public function __construct($formTypes, $storageTypeCollector, $defaultStorage)
    {
        $this->formTypes = $formTypes;
        $this->storageTypeCollector = $storageTypeCollector;
        $this->defaultStorage = $defaultStorage;
    }

    public function resolveName($type)
    {
        if (!array_key_exists($type, $this->formTypes)) {
            return null;
        }

        if (!isset($this->formTypes[$type]['storage']['type'])) {
            return $this->defaultStorage;
        }
        return $this->formTypes[$type]['storage']['type'];
    }

    public function resolve($type)
    {
        $name = $this->resolveName($type);
        return $this->storageTypeCollector->getType($name);
    }
}
