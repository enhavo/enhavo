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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author blutze-media
 */
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
    #[ORM\Column(nullable: true)]
    private ?int $position = 0;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }
}
