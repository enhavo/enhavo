<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:06
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class Navigation implements ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var NodeInterface
     */
    private $root;

    /**
     * @var string
     */
    private $code;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return NodeInterface|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param NodeInterface $root
     */
    public function setRoot(NodeInterface $root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}
