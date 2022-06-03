<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-02
 * Time: 17:41
 */

namespace App\Entity;

use App\Repository\PersonRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class Person
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="app_person")
 */
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'app_person')]
class Person implements ResourceInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $birthday;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $name;

    /**
     * @ORM\ManyToOne (targetEntity="Enhavo\Bundle\TaxonomyBundle\Entity\Term", cascade={"persist", "remove", "refresh"})
     */
    #[ORM\ManyToOne(
        targetEntity: Term::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    private ?Term $occupation;

    /**
     * @ORM\ManyToOne (targetEntity="Enhavo\Bundle\MediaBundle\Model\FileInterface", cascade={"persist", "remove", "refresh"})
     */
    #[ORM\ManyToOne(
        targetEntity: FileInterface::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    private ?FileInterface $picture;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime|null $birthday
     */
    public function setBirthday(?DateTime $birthday): void
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

    /**
     * @return FileInterface|null
     */
    public function getPicture(): ?FileInterface
    {
        return $this->picture;
    }

    /**
     * @param FileInterface|null $picture
     */
    public function setPicture(?FileInterface $picture): void
    {
        $this->picture = $picture;
    }
}
