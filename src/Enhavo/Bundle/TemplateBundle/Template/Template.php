<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-25
 * Time: 22:43
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

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     */
    public function setTranslationDomain(string $translationDomain): void
    {
        $this->translationDomain = $translationDomain;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getResourceTemplate(): string
    {
        return $this->resourceTemplate;
    }

    /**
     * @param string $resourceTemplate
     */
    public function setResourceTemplate(string $resourceTemplate): void
    {
        $this->resourceTemplate = $resourceTemplate;
    }
}
