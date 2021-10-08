<?php

namespace Enhavo\Bundle\UserBundle\Configuration\Attribute;

trait RedirectRouteTrait
{
    /** @var ?string */
    private $redirectRoute;

    /**
     * @return string|null
     */
    public function getRedirectRoute(): ?string
    {
        return $this->redirectRoute;
    }

    /**
     * @param string|null $redirectRoute
     */
    public function setRedirectRoute(?string $redirectRoute): void
    {
        $this->redirectRoute = $redirectRoute;
    }
}
