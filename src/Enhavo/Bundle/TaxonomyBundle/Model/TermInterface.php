<?php

/**
 * TaxonomyInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TaxonomyBundle\Model;

use Doctrine\Common\Collections\Collection;

interface TermInterface
{
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text);

    /**
     * Get text
     *
     * @return string
     */
    public function getText();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     */
    public function setSlug($slug);

    /**
     * @return TaxonomyInterface
     */
    public function getTaxonomy(): TaxonomyInterface;

    /**
     * @param TaxonomyInterface $taxonomy
     */
    public function setTaxonomy(TaxonomyInterface $taxonomy): void;

    /**
     * @return int
     */
    public function getPosition(): ?int;

    /**
     * @param int $position
     */
    public function setPosition(int $position): void;

    /**
     * @return TermInterface
     */
    public function getParent(): ?TermInterface;

    /**
     * @param TermInterface|null $parent
     * @return void
     */
    public function setParent(?TermInterface $parent): void;

    /**
     * @param TermInterface $child
     */
    public function addChildren(TermInterface $child);
    /**
     * @param TermInterface $child
     */
    public function removeChildren(TermInterface $child);

    /**
     * @return Collection|TermInterface[]
     */
    public function getChildren();
}
