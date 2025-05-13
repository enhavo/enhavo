<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Resource;

class Metadata
{
    public function __construct(
        private readonly string $name,
        private readonly array $config,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->getName();
    }

    public function getModelClass(): string
    {
        return $this->config['classes']['model'];
    }

    public function getRepositoryClass(): string
    {
        return $this->config['classes']['repository'];
    }

    public function getFactoryClass(): string
    {
        return $this->config['classes']['factory'];
    }

    public function getLabel(): ?string
    {
        return $this->config['label'] ?? null;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->config['translation_domain'] ?? null;
    }
}
