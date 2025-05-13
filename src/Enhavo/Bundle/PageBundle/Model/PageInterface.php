<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

interface PageInterface
{
    public function setContent(?NodeInterface $content = null): PageInterface;

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
