<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 19:59
 */

namespace Enhavo\Bundle\ContentBundle\Model;

use Enhavo\Bundle\RoutingBundle\Model\Routeable;

interface RedirectInterface extends Routeable
{
    public function setFrom($url);
    public function getFrom();
    public function setTo($url);
    public function getTo();
    public function setCode($code);
    public function getCode();
}