<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 11:21
 */

namespace Enhavo\Component\Type;


class RegistryEntry
{
    /** @var string */
    private $id;

    /** @var string */
    private $class;

    /** @var null|string */
    private $name;

    /** @var TypeInterface|null */
    private $service;

    /**
     * Entry constructor.
     * @param string $id
     * @param string $class
     * @param string $name
     */
    public function __construct(string $id, string $class, ?string $name = null)
    {
        $this->id = $id;
        $this->class = $class;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return TypeInterface|null
     */
    public function getService(): ?TypeInterface
    {
        return $this->service;
    }

    /**
     * @param TypeInterface|null $service
     */
    public function setService(?TypeInterface $service): void
    {
        $this->service = $service;
    }
}
