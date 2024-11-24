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
#[ORM\Table(name: 'app_table_row')]
class TableRow
{
    #[ORM\Column,ORM\Id,ORM\GeneratedValue]
    private ?int $id = null;
    #[ORM\OneToMany(mappedBy: 'row', targetEntity: TableCell::class, cascade: ['persist', 'refresh', 'remove'])]
    private Collection $children;
    #[ORM\ManyToOne(targetEntity: Table::class, inversedBy: 'children')]
    private ?Table $table = null;
    #[ORM\Column]
    private int $position = 0;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTable(): ?Table
    {
        return $this->table;
    }

    public function setTable(?Table $table): void
    {
        $this->table = $table;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(TableCell $child): void
    {
        $child->setRow($this);
        $this->children->add($child);
    }

    public function removeChild(TableCell $child): void
    {
        $child->setRow(null);
        $this->children->removeElement($child);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

}
