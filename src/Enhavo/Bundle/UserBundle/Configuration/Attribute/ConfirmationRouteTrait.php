<?php

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
