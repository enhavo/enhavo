<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait ConfirmationRouteTrait
{
    /** @var ?string */
    private $confirmationRoute;

    /**
     * @return string|null
     */
    public function getConfirmationRoute(): ?string
    {
        return $this->confirmationRoute;
    }

    /**
     * @param string|null $confirmationRoute
     */
    public function setConfirmationRoute(?string $confirmationRoute): void
    {
        $this->confirmationRoute = $confirmationRoute;
    }
}
