<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 10:32
 */

namespace Enhavo\Bundle\AppBundle\View;

class Component
{
    /** @var string */
    private $key;

    /** @var ViewData */
    private $viewData;

    /** @var string */
    private $component;

    /**
     * Component constructor.
     * @param string $key
     * @param ViewData $viewData
     * @param string $component
     */
    public function __construct(string $key, ViewData $viewData, string $component)
    {
        $this->key = $key;
        $this->viewData = $viewData;
        $this->component = $component;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return ViewData
     */
    public function getViewData(): ViewData
    {
        return $this->viewData;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component;
    }
}
