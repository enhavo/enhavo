<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function setRoute(?RouteInterface $route = null): void;

    public function getRoute(): ?RouteInterface;
}
