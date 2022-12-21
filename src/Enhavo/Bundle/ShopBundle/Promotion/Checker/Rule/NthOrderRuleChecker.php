<?php

namespace Enhavo\Bundle\ShopBundle\Promotion\Checker\Rule;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Repository\OrderRepository;
use Sylius\Component\Promotion\Checker\Rule\RuleCheckerInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

class NthOrderRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'nth_order';

    public function __construct(
        private OrderRepository $orderRepository,
    )
    {
    }

    /**
     * @throws UnsupportedTypeException
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnsupportedTypeException($subject, OrderInterface::class);
        }

        if (!isset($configuration['nth']) || !is_int($configuration['nth'])) {
            return false;
        }

        $user = $subject->getUser();
        if (null === $user) {
            return false;
        }

        return intval($this->orderRepository->countByUser($user, $configuration['from'], $configuration['to'])) === ($configuration['nth'] - 1);
    }
}
