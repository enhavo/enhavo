<?php
/**
 * @author blutze-media
 * @since 2024-11-06
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_table')]
class Table
{
    #[ORM\Column,ORM\Id,ORM\GeneratedValue]
    private ?int $id = null;
    #[ORM\Column]
    private ?string $name = null;
    #[ORM\OneToMany(mappedBy: 'table', targetEntity: TableRow::class, cascade: ['persist', 'refresh', 'remove'])]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(TableRow $child): void
    {
        $child->setTable($this);
        $this->children->add($child);
    }

    public function removeChild(TableRow $child): void
    {
        $child->setTable(null);
        $this->children->removeElement($child);
    }
}
