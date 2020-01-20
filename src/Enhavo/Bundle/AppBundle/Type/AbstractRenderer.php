<?php

namespace Enhavo\Bundle\AppBundle\Type;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

abstract class AbstractRenderer extends AbstractExtension
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
            new TwigFunction($this->getName(), array($this, 'render'), array('is_safe' => array('html'))),
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
