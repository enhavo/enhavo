<?php

/**
 * TaxonomyInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TaxonomyBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;

interface TermInterface
{
    public function setName(?string $name);

    public function getName(): ?string;

    public function setText(?string $text);

    public function getText(): ?string;

    public function getSlug(): ?string;

    public function setSlug(?string $slug);

    public function getTaxonomy(): TaxonomyInterface;

    public function setTaxonomy(TaxonomyInterface $taxonomy): void;

    public function getPosition(): ?int;

    public function setPosition(int $position): void;

    public function getParent(): ?TermInterface;

    /** @return Term[] */
    public function getParents(): array;

    public function setParent(?TermInterface $parent): void;

    public function addChildren(TermInterface $child);

    public function removeChildren(TermInterface $child);

    /** @return Collection|TermInterface[] */
    public function getChildren(): Collection;

    public function getDescendants(): Collection;
}
