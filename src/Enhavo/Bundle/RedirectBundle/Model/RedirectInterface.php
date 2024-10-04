<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 19:59
 */

namespace Enhavo\Bundle\RedirectBundle\Model;

use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

interface RedirectInterface extends Routeable
{
    public function setFrom(?string $value): void;
    public function getFrom(): ?string;
    public function setTo(?string $value): void;
    public function getTo(): ?string;
    public function setCode(?int $code): void;
    public function getCode(): ?int;
    public function setRoute(RouteInterface $route = null): void;
    public function getRoute(): ?RouteInterface;
}
