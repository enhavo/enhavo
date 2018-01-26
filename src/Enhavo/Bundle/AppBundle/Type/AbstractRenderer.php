<?php

namespace Enhavo\Bundle\AppBundle\Type;

abstract class AbstractRenderer extends \Twig_Extension
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * AbstractRenderer constructor.
     *
     * @param CollectorInterface $collector
     */
    public function __construct(CollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction($this->getName(), array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Return the Type
     *
     * @param $type
     * @return TypeInterface
     */
    protected function getType($type)
    {
        return $this->collector->getType($type);
    }
} 