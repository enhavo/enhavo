<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 23:40
 */

namespace Enhavo\Component\DoctrineExtension\Mapping;


class ExtensionRegistry
{
    /** @var MappingExtensionInterface[] */
    private $extensions = [];

    public function register(MappingExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }
}
