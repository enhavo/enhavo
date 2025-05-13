<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Prototype;

use Symfony\Component\Form\FormInterface;

class Prototype
{
    /** @var string */
    private $storageName;

    /** @var string */
    private $name;

    /** @var FormInterface */
    private $form;

    /** @var array */
    private $parameters;

    /**
     * Prototype constructor.
     */
    public function __construct(string $storageName, string $name, FormInterface $form, array $parameters)
    {
        $this->storageName = $storageName;
        $this->name = $name;
        $this->form = $form;
        $this->parameters = $parameters;
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
