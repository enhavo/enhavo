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
use Sylius\Component\Originator\Model\OriginAwareInterface;
use Sylius\Component\Originator\Originator\OriginatorInterface;
use Sylius\Component\Promotion\Action\PromotionActionInterface;
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

        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType($this->getAdjustmentType());
        $adjustment->setOriginId($promotion->getOriginId());
        $adjustment->setOriginType($promotion->getOriginType());

        $this->configureAdjustmentAmount($adjustment, $subject, $configuration);

        $subject->addAdjustment($adjustment);
    }

    abstract protected function configureAdjustmentAmount(AdjustmentInterface $adjustment, OrderInterface $subject, array $configuration);

    protected function getAdjustmentType()
    {
        return \Sylius\Component\Core\Model\AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT;
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
                $adjustment->getOriginType() ===  $promotion->getOriginType() &&
                $adjustment->getType() === $this->getAdjustmentType()
            ) {
                $subject->removeAdjustment($adjustment);
                break;
            }
        }
    }

    public function getConfigurationFormType()
    {
        return null;
    }
}