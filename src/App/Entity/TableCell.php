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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @author blutze-media
 */
#[Entity]
#[ORM\Table(name: 'app_table_cell')]
class TableCell
{
    #[Column,Id,GeneratedValue]
    private ?int $id = null;
    #[Column(nullable: true)]
    private string $name;
    #[Column(nullable: true)]
    private string $value;
    #[Column(nullable: true)]
    private ?int $position = 0;
    #[ORM\ManyToOne(targetEntity: TableRow::class, inversedBy: 'children')]
    private ?TableRow $row = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRow(): ?TableRow
    {
        return $this->row;
    }

    public function setRow(?TableRow $row): void
    {
        $this->row = $row;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
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
