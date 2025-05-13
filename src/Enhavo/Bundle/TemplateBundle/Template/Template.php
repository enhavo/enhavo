<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Template;

class Template
{
    /**
     * @var string
     */
    private $repository;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $resourceTemplate;

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getResourceTemplate(): string
    {
        return $this->resourceTemplate;
    }

    public function setResourceTemplate(string $resourceTemplate): void
    {
        $this->resourceTemplate = $resourceTemplate;
    }
}
