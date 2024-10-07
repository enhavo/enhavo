<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 14:45
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
    )
    {
        $this->access = $defaultAccess;
    }

    public function isAccess(): bool
    {
        if ($this->isResolved) {
            return $this->access;
        }

        $request = $this->requestStack->getMainRequest();
        if ($request === null) {
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
