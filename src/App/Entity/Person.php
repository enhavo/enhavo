<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-02
 * Time: 17:41
 */

namespace App\Entity;

use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Person
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="app_person")
 */
class Person implements ResourceInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var null|integer
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime|null
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private $name;

    /**
     * @ORM\ManyToOne (targetEntity="Enhavo\Bundle\TaxonomyBundle\Entity\Term", cascade={"persist", "remove", "refresh"})
     * @var Term|null
     */
    private $occupation;

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

    /**
     * @return Term|null
     */
    public function getOccupation(): ?Term
    {
        return $this->occupation;
    }

    /**
     * @param Term|null $occupation
     */
    public function setOccupation(?Term $occupation): void
    {
        $this->occupation = $occupation;
    }
}
