<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(string $name, Strategy $strategy, string $model, array $formConfig)
    {
        $this->name = $name;
        $this->strategy = $strategy;
        $this->model = $model;
        $this->formConfig = $formConfig;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStrategy(): Strategy
    {
        return $this->strategy;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getFormConfig(): array
    {
        return $this->formConfig;
    }

    public function getGroups(): array
    {
        return [];
    }
}
