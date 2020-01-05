<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-02
 * Time: 17:41
 */

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Person implements ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime|null
     */
    private $birthday;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime|null $birthday
     */
    public function setBirthday(?\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
