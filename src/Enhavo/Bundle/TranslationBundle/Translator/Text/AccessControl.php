<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-29
 * Time: 14:45
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Text;


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
    private $controlList;

    /**
     * AccessControl constructor.
     * @param RequestStack $requestStack
     * @param LocaleResolverInterface $localResolver
     * @param string[] $controlList
     */
    public function __construct(RequestStack $requestStack, LocaleResolverInterface $localResolver, array $controlList)
    {
        $this->requestStack = $requestStack;
        $this->localResolver = $localResolver;
        $this->controlList = $controlList;
    }

    public function isAccess()
    {

    }

    public function getLocale()
    {

    }
}
