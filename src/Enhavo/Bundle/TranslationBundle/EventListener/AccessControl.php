<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 14:45
 */

namespace Enhavo\Bundle\TranslationBundle\EventListener;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AccessControl
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var LocaleResolverInterface
     */
    private $localResolver;

    /**
     * @var string[]
     */
    private $accessControl;

    /**
     * @var boolean
     */
    private $access;

    /**
     * @var string
     */
    private $locale;

    /**
     * AccessControl constructor.
     * @param RequestStack $requestStack
     * @param LocaleResolverInterface $localResolver
     * @param string[] $accessControl
     */
    public function __construct(RequestStack $requestStack, LocaleResolverInterface $localResolver, array $accessControl)
    {
        $this->requestStack = $requestStack;
        $this->localResolver = $localResolver;
        $this->accessControl = $accessControl;
    }

    public function isAccess(): bool
    {
        if ($this->access !== null) {
            return $this->access;
        }

<<<<<<< HEAD
        $this->access = true;
        $request = $this->requestStack->getMasterRequest();
=======
        $request = $this->requestStack->getMainRequest();
>>>>>>> 850d2a42f (Avoid AccessControl to set access to true per default (#2083))
        if ($request === null) {
            $this->access = false;
            return $this->access;
        }

        $this->access = true;
        $path = $request->getPathInfo();
        foreach ($this->accessControl as $regex) {
            if (!preg_match($regex, $path)) {
                $this->access = false;
                break;
            }
        }

        return $this->access;
    }

    public function getLocale()
    {
        if ($this->locale !== null) {
            return $this->locale;
        }
        $this->locale = $this->localResolver->resolve();

        return $this->locale;
    }
}
