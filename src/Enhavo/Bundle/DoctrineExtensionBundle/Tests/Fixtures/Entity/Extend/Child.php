<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Child extends Entity
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street): void
    {
        $this->street = $street;
    }
}
