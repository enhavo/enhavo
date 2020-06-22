<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:10
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Metadata;


class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /** @var string|null */
    private $extends;

    /** @var string|null */
    private $discrName;

    /** @var Reference[] */
    private $references = [];

    /**
     * @return string
     */
    public function getExtends(): ?string
    {
        return $this->extends;
    }

    /**
     * @param string $extends
     */
    public function setExtends(?string $extends): void
    {
        $this->extends = $extends;
    }

    /**
     * @return string
     */
    public function getDiscrName(): ?string
    {
        return $this->discrName;
    }

    /**
     * @param string $discrName
     */
    public function setDiscrName(?string $discrName): void
    {
        $this->discrName = $discrName;
    }

    /**
     * @param Reference $reference
     */
    public function addReference(Reference $reference)
    {
        $this->references[] = $reference;
    }


    /**
     * @return Reference[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }
}
