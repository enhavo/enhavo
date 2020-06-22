<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:30
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 */
class Entity extends Root
{
    /**
     * @Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }
}
