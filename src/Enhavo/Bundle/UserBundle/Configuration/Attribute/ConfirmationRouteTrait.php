<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait ConfirmationRouteTrait
{
    private ?string $confirmationRoute = null;

    public function getConfirmationRoute(): ?string
    {
        return $this->confirmationRoute;
    }

    public function setConfirmationRoute(?string $confirmationRoute): void
    {
        $this->confirmationRoute = $confirmationRoute;
    }
}
