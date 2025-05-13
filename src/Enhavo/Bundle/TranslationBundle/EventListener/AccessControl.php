<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;

class AccessControl implements AccessControlInterface
{
    private bool $access;
    private bool $isResolved = false;

    public function __construct(
        private RequestStack $requestStack,
        private array $accessControl,
        private $defaultAccess = true,
    ) {
        $this->access = $defaultAccess;
    }

    public function isAccess(): bool
    {
        if ($this->isResolved) {
            return $this->access;
        }

        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return false;
        }

        $path = $request->getPathInfo();
        foreach ($this->accessControl as $regex) {
            if (preg_match($regex, $path) != $this->defaultAccess) {
                $this->access = !$this->defaultAccess;
                break;
            }
        }

        $this->isResolved = true;

        return $this->access;
    }
}
