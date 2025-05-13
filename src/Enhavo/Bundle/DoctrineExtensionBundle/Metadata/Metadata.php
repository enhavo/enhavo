<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function setExtends(?string $extends): void
    {
        $this->extends = $extends;
    }

    public function getDiscrName(): ?string
    {
        return $this->discrName;
    }

    public function setDiscrName(?string $discrName): void
    {
        $this->discrName = $discrName;
    }

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
