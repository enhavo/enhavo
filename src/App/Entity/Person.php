<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Attribute\Duplicate;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Model\RevisionTrait;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'app_person')]
class Person implements RevisionInterface
{
    use RevisionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Duplicate('clone', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?\DateTime $birthday = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Duplicate('string', ['postfix' => ' Copy!!', 'groups' => ['duplicate']])]
    #[Duplicate('property', ['groups' => ['revision', 'restore']])]
    private ?string $name = null;

    #[ORM\ManyToOne(
        targetEntity: Term::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Duplicate('reference', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?Term $occupation = null;

    #[ORM\ManyToOne(
        targetEntity: Term::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Duplicate('reference', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?Term $category = null;

    #[ORM\ManyToOne(
        targetEntity: Term::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Duplicate('reference', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?Term $otherCategory = null;

    #[ORM\ManyToOne(
        targetEntity: FileInterface::class,
        cascade: ['persist', 'remove', 'refresh']
    )]
    #[Duplicate('file', ['groups' => ['duplicate', 'revision', 'restore']])]
    private ?FileInterface $picture = null;

    #[ORM\ManyToOne(
        targetEntity: Person::class,
        inversedBy: 'revisions',
    )]
    private ?RevisionInterface $revisionSubject = null;

    #[ORM\OneToMany(
        mappedBy: 'revisionSubject',
        targetEntity: Person::class,
        cascade: ['persist', 'refresh'],
    )]
    private Collection $revisions;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $revisionDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $revisionState = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $revisionParameters = [];

    #[ORM\ManyToOne(
        targetEntity: \Enhavo\Bundle\UserBundle\Model\UserInterface::class,
    )]
    private ?UserInterface $revisionUser = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $amountChildren = null;

    public function __construct()
    {
        $this->revisions = new ArrayCollection();
    }

    public function getRevisionTitle(): ?string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthday(): ?\DateTime
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTime $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getOccupation(): ?Term
    {
        return $this->occupation;
    }

    public function setOccupation(?Term $occupation): void
    {
        $this->occupation = $occupation;
    }

    public function getPicture(): ?FileInterface
    {
        return $this->picture;
    }

    public function setPicture(?FileInterface $picture): void
    {
        $this->picture = $picture;
    }

    public function getCategory(): ?Term
    {
        return $this->category;
    }

    public function setCategory(?Term $category): void
    {
        $this->category = $category;
    }

    public function getOtherCategory(): ?Term
    {
        return $this->otherCategory;
    }

    public function getAmountChildren(): ?int
    {
        return $this->amountChildren;
    }

    public function setAmountChildren(?int $amountChildren): void
    {
        $this->amountChildren = $amountChildren;
    }
}
