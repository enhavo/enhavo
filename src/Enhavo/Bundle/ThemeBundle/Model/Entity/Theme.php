<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:50
 */

namespace Enhavo\Bundle\ThemeBundle\Model\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Theme implements ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var boolean
     */
    private $active = false;

    /**
     * @var \Enhavo\Bundle\ThemeBundle\Model\Theme
     */
    private $theme;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return \Enhavo\Bundle\ThemeBundle\Model\Theme
     */
    public function getTheme(): ?\Enhavo\Bundle\ThemeBundle\Model\Theme
    {
        return $this->theme;
    }

    /**
     * @param \Enhavo\Bundle\ThemeBundle\Model\Theme $theme
     */
    public function setTheme(?\Enhavo\Bundle\ThemeBundle\Model\Theme $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}
