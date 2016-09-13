<?php
/**
 * CountryResolverInterface.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Locale;

use Sylius\Component\Addressing\Model\CountryInterface;

interface CountryResolverInterface
{
    /**
     * @return CountryInterface|null
     */
    public function resolve();

    /**
     * @return string|null
     */
    public function resolveCode();
}