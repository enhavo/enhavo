<?php

namespace Enhavo\Bundle\RevisionBundle\Tests\Mock;

use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionTrait;

class RevisionMock implements RevisionInterface
{
    use RevisionTrait;

    public function getRevisionTitle(): ?string
    {
        return self::class;
    }
}

class NoRevisionMock
{

}
