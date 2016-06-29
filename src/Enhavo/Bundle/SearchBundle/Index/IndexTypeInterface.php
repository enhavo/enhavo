<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:14
 */

namespace Enhavo\Bundle\SearchBundle\Index;


use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface IndexTypeInterface extends TypeInterface
{
    function index($value, $options);
}