<?php
/*
 * Subscription.php
 *
 * @since 04.09.20, 12:44
 * @author blutze
 */

namespace Enhavo\Bundle\NewsletterBundle\Subscription;

use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;

class Subscription
{
    /** @var string */
    private $name;

    /** @var Strategy */
    private $strategy;

    /** @var string */
    private $model;

    /** @var array */
    private $formConfig;

    /**
     * Subscription constructor.
     * @param string $name
     * @param Strategy $strategy
     * @param string $model
     * @param array $formConfig
     */
    public function __construct(string $name, Strategy $strategy, string $model, array $formConfig)
    {
        $this->name = $name;
        $this->strategy = $strategy;
        $this->model = $model;
        $this->formConfig = $formConfig;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return Strategy
     */
    public function getStrategy(): Strategy
    {
        return $this->strategy;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getFormConfig(): array
    {
        return $this->formConfig;
    }

    public function getGroups(): array
    {
        return [];
    }


}
