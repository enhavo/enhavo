<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 18:04
 */

namespace Enhavo\Bundle\FormBundle\Prototype;

class PrototypeStorage
{
    private $prototypes = [];

    public function setPrototypes($key, $prototypes)
    {
        $this->prototypes[$key] = $prototypes;
    }

    public function getPrototypes($key): array
    {
        return $this->prototypes[$key];
    }

    public function hasPrototypes($key): bool
    {
        return array_key_exists($key, $this->prototypes);
    }
}
