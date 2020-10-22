<?php

namespace Enhavo\Bundle\ContactBundle\Entity;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class ContactBlock extends AbstractBlock
{
    /**
     * @var string|null
     */
    private $key;

    /**
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }
}
