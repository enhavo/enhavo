<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:01
 */

namespace Enhavo\Bundle\TemplateBundle\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;
use Enhavo\Bundle\GridBundle\Model\GridInterface;
use Enhavo\Bundle\ContentBundle\Entity\Content;

class Template implements ResourceInterface
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
     * @var GridInterface
     */
    private $grid;

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
     * @param GridInterface $grid
     * @return Template
     */
    public function setGrid(GridInterface $grid = null)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * Get content
     * 
     * @return GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }
}