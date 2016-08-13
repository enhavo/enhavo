<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 6/6/16
 * Time: 10:02 AM
 */

namespace Enhavo\Bundle\ShopBundle\Entity;


class Product
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}