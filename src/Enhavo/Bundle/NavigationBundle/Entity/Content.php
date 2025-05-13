<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Content implements SubjectInterface
{
    /** @var int|null */
    private $id;

    /** @var NodeInterface|null */
    private $node;

    /** @var object|null */
    private $content;

    /** @var string|null */
    private $contentClass;

    /** @var int|null */
    private $contentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNode(): ?NodeInterface
    {
        return $this->node;
    }

    public function setNode(?NodeInterface $node): void
    {
        $this->node = $node;
    }

    public function getContent(): ?object
    {
        return $this->content;
    }

    public function setContent(?object $content): void
    {
        $this->content = $content;
    }

    public function getContentClass(): ?string
    {
        return $this->contentClass;
    }

    public function setContentClass(?string $contentClass): void
    {
        $this->contentClass = $contentClass;
    }

    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    public function setContentId(?int $contentId): void
    {
        $this->contentId = $contentId;
    }
}
