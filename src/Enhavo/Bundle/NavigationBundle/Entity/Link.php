<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 16:36
 */

namespace Enhavo\Bundle\NavigationBundle\Entity;


class Link
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $link;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}