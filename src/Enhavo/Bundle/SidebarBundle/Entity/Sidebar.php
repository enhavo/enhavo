<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:01
 */

namespace Enhavo\Bundle\SidebarBundle\Entity;

use Enhavo\Bundle\SidebarBundle\Model\SidebarInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Enhavo\Bundle\BlockBundle\Model\ContainerInterface;

class Sidebar implements ResourceInterface, SidebarInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $code;

    /**
     * @var ContainerInterface
     */
    private $content;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param $code string
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set content
     *
     * @param ContainerInterface $content
     * @return Sidebar
     */
    public function setContent(ContainerInterface $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return ContainerInterface
     */
    public function getContent()
    {
        return $this->content;
    }
}
