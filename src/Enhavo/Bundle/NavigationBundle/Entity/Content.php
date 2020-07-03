<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-02
 * Time: 10:45
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Content implements SubjectInterface
{
    /** @var integer|null */
    private $id;

    /** @var NodeInterface|null */
    private $node;

    /** @var object|null */
    private $content;

    /** @var string|null */
    private $contentClass;

    /** @var integer|null */
    private $contentId;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return NodeInterface|null
     */
    public function getNode(): ?NodeInterface
    {
        return $this->node;
    }

    /**
     * @param NodeInterface|null $node
     */
    public function setNode(?NodeInterface $node): void
    {
        $this->node = $node;
    }

    /**
     * @return object|null
     */
    public function getContent(): ?object
    {
        return $this->content;
    }

    /**
     * @param object|null $content
     */
    public function setContent(?object $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getContentClass(): ?string
    {
        return $this->contentClass;
    }

    /**
     * @param string|null $contentClass
     */
    public function setContentClass(?string $contentClass): void
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @return int|null
     */
    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    /**
     * @param int|null $contentId
     */
    public function setContentId(?int $contentId): void
    {
        $this->contentId = $contentId;
    }
}
