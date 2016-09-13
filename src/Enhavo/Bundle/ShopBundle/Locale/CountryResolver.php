<?php
/**
 * CountryResolver.php
 *
 * @since 12/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Locale;

use Sylius\Component\Resource\Repository\RepositoryInterface;

class CountryResolver implements CountryResolverInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $countryRepository;

    /**
     * @var string
     */
    protected $defaultCountryCode;

    /**
     * CountryResolver constructor.
     *
     * @param RepositoryInterface $countryRepository
     * @param string $defaultCountryCode
     */
    public function __construct(RepositoryInterface $countryRepository, $defaultCountryCode = 'DE')
    {
        $this->countryRepository = $countryRepository;
        $this->defaultCountryCode = $defaultCountryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        return $this->countryRepository->findOneBy(['code' => $this->defaultCountryCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveCode()
    {
        $country = $this->resolve();
        if($country) {
            return $country->getCode();
        }
        return null;
    }
}