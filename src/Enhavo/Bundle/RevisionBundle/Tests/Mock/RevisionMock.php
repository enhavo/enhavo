<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
