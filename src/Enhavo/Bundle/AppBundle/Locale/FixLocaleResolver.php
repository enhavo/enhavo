<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-26
 * Time: 00:54
 */

namespace Enhavo\Bundle\AppBundle\Locale;


class FixLocaleResolver implements LocaleResolverInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * FixLocaleResolver constructor.
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function resolve()
    {
        return $this->locale;
    }
}
