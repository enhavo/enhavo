<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.06.18
 * Time: 11:34
 */

namespace Enhavo\Bundle\PageBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

interface PageInterface
{
    public function setContent(NodeInterface $content = null): PageInterface;

    public function getContent(): ?NodeInterface;

    public function getParent(): ?PageInterface;

    public function setParent(?PageInterface $parent): PageInterface;

    public function addChild(PageInterface $page): PageInterface;

    public function getChildren(): Collection;

    public function removeChild(PageInterface $page): PageInterface;

    public function getSpecial(): ?string;

    public function setSpecial(?string $special): void;

    public function getType(): ?string;

    public function setType(?string $type): void;
}
