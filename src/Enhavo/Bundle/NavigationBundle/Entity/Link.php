<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 16:36
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Model\SubjectInterface;

class Link implements SubjectInterface
{
    /** @var integer */
    private $id;

    /** @var string */
    private $link;

    /** @var string|null */
    private $target;

    /** @var Node|null */
    private $node;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string|null
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * @param string|null $target
     */
    public function setTarget(?string $target): void
    {
        $this->target = $target;
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
}
