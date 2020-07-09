<?php


namespace Enhavo\Bundle\AppBundle\Provider;


use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class ProviderManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    public function getProvider($type, $key)
    {
        return $this->collector->getType(sprintf('%s.%s', $type, $key));
    }
}
