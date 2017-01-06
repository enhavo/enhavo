<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 03.12.16
 * Time: 17:16
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\NewsletterBundle\Subscriber\SubscriberManager;

class StrategyResolver
{
    /**
     * @var array
     */
    private $formTypes;

    /**
     * @var TypeCollector
     */
    private $strategyTypeCollector;

    /**
     * @var StrategyInterface
     */
    private $defaultStrategy;

    public function __construct($formTypes, $strategyTypeCollector, $defaultStrategy)
    {
        $this->formTypes = $formTypes;
        $this->strategyTypeCollector = $strategyTypeCollector;
        $this->defaultStrategy = $defaultStrategy;
    }

    public function resolveName($type)
    {
        if (!array_key_exists($type, $this->formTypes)) {
            return null;
        }

        if (!isset($this->formTypes[$type]['strategy']['type'])) {
            return $this->defaultStrategy;
        }
        return $this->formTypes[$type]['strategy']['type'];
    }

    public function resolve($type)
    {
        $name = $this->resolveName($type);
        return $this->strategyTypeCollector->getType($name);
    }
}