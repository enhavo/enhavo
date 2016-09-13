<?php


namespace Enhavo\Bundle\ShopBundle\Promotion\Checker;

use Enhavo\Bundle\ShopBundle\Locale\CountryResolverInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Promotion\Checker\RuleCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;

class ShippingCountryRuleChecker implements RuleCheckerInterface
{
    /**
     * @var CountryResolverInterface
     */
    private $countryResolver;

    /**
     * ShippingCountryRuleChecker constructor.
     *
     * @param CountryResolverInterface $countryResolver
     */
    public function __construct(CountryResolverInterface $countryResolver)
    {
        $this->countryResolver = $countryResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration)
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnsupportedTypeException($subject, OrderInterface::class);
        }

        if (null === $address = $subject->getShippingAddress()) {
            return false;
        }

        return $this->isTargetCountry($subject, $configuration['country']);
    }

    protected function isTargetCountry(OrderInterface $order, $country)
    {
        $address = $order->getShippingAddress();
        if($address) {
            return $address && $address->getCountryCode() === $country;
        } else {
            return $this->countryResolver->resolveCode() === $country;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormType()
    {
        return null;
    }
}
