<?php
/**
 * Order.php
 *
 * @since 14/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderItemInterface;
use Enhavo\Bundle\ShopBundle\State\OrderCheckoutStates;
use Enhavo\Bundle\ShopBundle\State\OrderPaymentStates;
use Enhavo\Bundle\ShopBundle\State\OrderShippingStates;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ShipmentInterface;
use Enhavo\Bundle\ShopBundle\Model\AdjustmentInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Order\Model\Order as SyliusOrder;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;

class Order extends SyliusOrder implements OrderInterface
{
    use AddressSubjectTrait;

    private string $paymentState = OrderPaymentStates::STATE_CART;
    private string $shippingState = OrderShippingStates::STATE_CART;
    private string $checkoutState = OrderCheckoutStates::STATE_CART;

    private ?PromotionCouponInterface $promotionCoupon= null;
    private ?UserInterface $user = null;

    private ?string $email = null;
    private ?string $token = null;
    private ?bool $trackingMail = false;
    private ?bool $shippable = true;
    private ?string $currencyCode = null;

    /** @var Collection|PaymentInterface[] */
    private $payments;

    /** @var Collection|ShipmentInterface[] */
    private $shipments;

    /** @var Collection */
    private $promotions;

    /** @var Collection */
    private $vouchers;

    public function __construct()
    {
        parent::__construct();
        $this->promotions = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->shipments = new ArrayCollection();
        $this->vouchers = new ArrayCollection();
    }

    public function setPaymentState(?string $paymentState): void
    {
        $this->paymentState = $paymentState;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function setShippingState(?string $shippingState): void
    {
        $this->shippingState = $shippingState;
    }

    public function getShippingState(): ?string
    {
        return $this->shippingState;
    }

    public function setPromotionCoupon(?PromotionCouponInterface $promotionCoupon = null)
    {
        $this->promotionCoupon = $promotionCoupon;
    }

    public function getPromotionCoupon(): ?PromotionCouponInterface
    {
        return $this->promotionCoupon;
    }

    public function addPromotion(PromotionInterface $promotions): void
    {
        $this->promotions[] = $promotions;
    }

    public function removePromotion(PromotionInterface $promotions): void
    {
        $this->promotions->removeElement($promotions);
    }

    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher($voucher)
    {
        $this->vouchers->add($voucher);
    }

    public function removeVoucher($voucher)
    {
        $this->vouchers->removeElement($voucher);
    }

    public function getPromotionSubjectTotal(): int
    {
        return $this->getItemsTotal();
    }

    public function hasPromotion(PromotionInterface $promotion): bool
    {
       return $this->promotions->contains($promotion);
    }

    public function getPromotionSubjectCount()
    {
        return $this->getTotalQuantity();
    }

    public function addPayment(PaymentInterface $payment)
    {
        $this->payments->add($payment);
        $payment->setOrder($this);
    }

    public function removePayment(PaymentInterface $payment)
    {
        $this->payments->removeElement($payment);
        $payment->setOrder(null);
    }

    /**
     * @return Collection|array<PaymentInterface>
     */
    public function getPayments(): Collection|array
    {
        return $this->payments;
    }

    public function addShipment(ShipmentInterface $shipment)
    {
        $this->shipments->add($shipment);
        $shipment->setOrder($this);
    }

    public function hasShipments(): bool
    {
        return $this->shipments->count() > 0;
    }

    public function removeShipment(ShipmentInterface $shipment)
    {
        $this->shipments->removeElement($shipment);
        $shipment->setOrder(null);
    }

    /**
     * @return Collection|array<ShipmentInterface>
     */
    public function getShipments(): Collection|array
    {
        return $this->shipments;
    }

    public function removeShipments()
    {
        $shipments = $this->getShipments();
        foreach ($shipments as $shipment) {
            $this->removeShipment($shipment);
        }
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getShippingTotal()
    {
        return $this->getShippingPrice() + $this->getShippingTax();
    }

    public function getShippingPrice()
    {
        $total = 0;
        $shippingAdjustments = $this->getAdjustments(AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $shippingPromotionAdjustments = $this->getAdjustments(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT);


        foreach($shippingAdjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }

        foreach($shippingPromotionAdjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }

        return $total;
    }

    public function getShippingTax()
    {
        $total = 0;
        $tax = $this->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
        foreach($tax as $adjustment) {
            if($adjustment->getOriginType() === ShipmentInterface::class) {
                $total += $adjustment->getAmount();
            }
        }
        return $total;
    }

    public function getDiscountTotal()
    {
        $total = 0;
        $adjustments = $this->getAdjustments(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        foreach($adjustments as $adjustment) {
            $total += $adjustment->getAmount();
        }

        return $total;
    }

    public function getUnitTotal()
    {
        $total = 0;
        foreach($this->getItems() as $items) {
            $total += $items->getTotal();
        }
        return $total;
    }

    public function getUnitPriceTotal()
    {
        $total = 0;
        foreach($this->getItems() as $items) {
            $total += $items->getUnitPriceTotal();
        }
        return $total;
    }

    public function getUnitTaxTotal()
    {
        $total = 0;
        foreach($this->getItems() as $items) {
            $total += $items->getTaxTotal();
        }
        return $total;
    }

    public function getTaxTotal()
    {
        $total = 0;
        $total += $this->getUnitTaxTotal();

        $taxes = $this->getAdjustments(AdjustmentInterface::TAX_ADJUSTMENT);
        foreach($taxes as $adjustment) {
            $total += $adjustment->getAmount();
        }

        $taxes = $this->getAdjustments(AdjustmentInterface::TAX_SHIPPING_ADJUSTMENT);
        foreach($taxes as $adjustment) {
            $total += $adjustment->getAmount();
        }

        return $total;
    }

    public function getCustomerEmail()
    {
        if($this->getUser() !== null) {
            return $this->getUser()->getEmail();
        }
        return $this->getEmail();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return boolean
     */
    public function isTrackingMail()
    {
        return $this->trackingMail;
    }

    /**
     * @param boolean $trackingMail
     */
    public function setTrackingMail($trackingMail)
    {
        $this->trackingMail = $trackingMail;
    }

    public function isFreeShipping()
    {
        return $this->getShippingTotal() === 0;
    }

    public function isPayed()
    {
        return $this->paymentState === PaymentInterface::STATE_COMPLETED;
    }

    public function getCheckoutState(): string
    {
        return $this->checkoutState;
    }

    public function setCheckoutState(string $checkoutState): void
    {
        $this->checkoutState = $checkoutState;
    }

    public function isShippable()
    {
        return $this->shippable;
    }

    public function setShippable($value)
    {
        $this->shippable = $value;
    }

    public function getAddress(): AddressSubjectInterface
    {
        return $this;
    }

    public function getItemUnits(): Collection
    {
        /** @var ArrayCollection<int, OrderItemInterface> $units */
        $units = new ArrayCollection();

        /** @var OrderItem $item */
        foreach ($this->getItems() as $item) {
            foreach ($item->getUnits() as $unit) {
                $units->add($unit);
            }
        }

        return $units;
    }

    public function getLastPayment(?string $state = null): ?PaymentInterface
    {
        if ($this->payments->isEmpty()) {
            return null;
        }

        $payment = $this->payments->filter(function (PaymentInterface $payment) use ($state): bool {
            return null === $state || $payment->getState() === $state;
        })->last();

        return $payment !== false ? $payment : null;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(?string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getItemsTaxTotal()
    {
        $tax = 0;
        foreach ($this->items as $item) {
            $adjustments = $item->getAdjustmentsRecursively(AdjustmentInterface::TAX_ADJUSTMENT);
            foreach ($adjustments as $adjustment) {
                $tax += $adjustment->getAmount();
            }
        }
        return $tax;
    }

    public function getVoucherAmountTotal()
    {
        $amount = 0;

        $adjustments = $this->getAdjustmentsRecursively(AdjustmentInterface::VOUCHER_ADJUSTMENT);
        foreach ($adjustments as $adjustment) {
            $amount += $adjustment->getAmount();
        }

        return $amount;
    }

    public function getItemsNetTotal()
    {
        return $this->getItemsTotal() - $this->getItemsTaxTotal();
    }
}
