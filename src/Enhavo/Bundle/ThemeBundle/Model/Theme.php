<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:50
 */

namespace Enhavo\Bundle\ThemeBundle\Model;


class Theme
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string[]
     */
    private $templates;
}
