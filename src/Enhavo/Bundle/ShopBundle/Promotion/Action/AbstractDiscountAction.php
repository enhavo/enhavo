<?php
/**
 * AbstractDiscountAction.php
 *
 * @since 03/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Promotion\Action;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Order\Model\AdjustmentInterface;
use Sylius\Component\Promotion\Model\PromotionActionInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

abstract class AbstractDiscountAction implements PromotionActionInterface
{
    /**
     * @var OriginatorInterface
     */
    protected $originator;

    /**
     * @var FactoryInterface
     */
    protected $adjustmentFactory;

    public function __construct(OriginatorInterface $originator, FactoryInterface $adjustmentFactory)
    {
        $this->originator = $originator;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        if(!$subject instanceof OrderInterface) {
            return;
        }

        if(!$promotion instanceof OriginAwareInterface) {
            return;
        }

        $adjustments = $this->configureAdjustments($subject, $configuration, $promotion);

        if(is_array($adjustments)) {
            foreach($adjustments as $adjustment) {
                $subject->addAdjustment($adjustment);
            }
        } elseif($adjustments instanceof AdjustmentInterface) {
            $subject->addAdjustment($adjustments);
        }
    }

    abstract protected function configureAdjustments(OrderInterface $subject, array $configuration, PromotionInterface $promotion);

    protected function createAdjustment(PromotionInterface $promotion)
    {
        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setOriginId($promotion->getOriginId());
        $adjustment->setOriginType($promotion->getOriginType());
        return $adjustment;
    }

    public function revert(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion)
    {
        if(!$subject instanceof OrderInterface) {
            return;
        }

        if(!$promotion instanceof OriginAwareInterface) {
            return;
        }

        /** @var AdjustmentInterface $adjustment */
        foreach($subject->getAdjustments() as $adjustment) {
            if(
                $adjustment->getOriginId() === $promotion->getOriginId() &&
                $adjustment->getOriginType() ===  $promotion->getOriginType()
            ) {
                $subject->removeAdjustment($adjustment);
            }
        }
    }

    public function getConfigurationFormType()
    {
        return null;
    }
}
