<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:30
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Child extends Entity
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }
}
