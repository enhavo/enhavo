<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class FormType
{
    public function __construct(
        private string $blockPrefix,
        private array $properties,
    ) {
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBlockPrefix(): string
    {
        return $this->blockPrefix;
    }

    public function getFields(): array
    {
        $fields = [];

        foreach ($this->properties as $key => $config) {
            $formConfig = $config['form'] ?? null;
            if ($formConfig) {
                $fields[] = new FormTypeField($key, $formConfig);
            }
        }

        return $fields;
    }
}
