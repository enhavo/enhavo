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
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Cart\Model\Cart;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Promotion\Model\CouponInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;

class Order extends Cart implements OrderInterface
{
    /**
     * @var string
     */
    private $checkoutState;

    /**
     * @var string
     */
    private $paymentState;

    /**
     * @var string
     */
    private $shippingState;

    /**
     * @var CouponInterface
     */
    private $promotionCoupon;

    /**
     * @var AddressInterface
     */
    private $shippingAddress;

    /**
     * @var AddressInterface
     */
    private $billingAddress;

    /**
     * @var boolean
     */
    private $differentBillingAddress;

    /**
     * @var \DateTime
     */
    private $orderedAt;

    /**
     * @var PaymentInterface
     */
    private $payment;

    /**
     * @var ShipmentInterface
     */
    private $shipment;

    /**
     * @var Collection
     */
    private $promotions;

    /**
     * @var string
     */
    private $email;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->promotions = new ArrayCollection();
    }

    /**
     * Set checkoutState
     *
     * @param string $checkoutState
     *
     * @return Order
     */
    public function setCheckoutState($checkoutState)
    {
        $this->checkoutState = $checkoutState;

        return $this;
    }

    /**
     * Get checkoutState
     *
     * @return string
     */
    public function getCheckoutState()
    {
        return $this->checkoutState;
    }

    /**
     * Set paymentState
     *
     * @param string $paymentState
     *
     * @return Order
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    /**
     * Get paymentState
     *
     * @return string
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * Set shippingState
     *
     * @param string $shippingState
     *
     * @return Order
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * Set promotionCoupon
     *
     * @param CouponInterface $promotionCoupon
     *
     * @return Order
     */
    public function setPromotionCoupon(CouponInterface $promotionCoupon = null)
    {
        $this->promotionCoupon = $promotionCoupon;

        return $this;
    }

    /**
     * Get promotionCoupon
     *
     * @return CouponInterface
     */
    public function getPromotionCoupon()
    {
        return $this->promotionCoupon;
    }

    /**
     * Set shippingAddress
     *
     * @param AddressInterface $shippingAddress
     *
     * @return Order
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null)
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return AddressInterface
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set billingAddress
     *
     * @param AddressInterface $billingAddress
     *
     * @return Order
     */
    public function setBillingAddress(AddressInterface $billingAddress = null)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return \Sylius\Component\Addressing\Model\Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set differentBillingAddress
     *
     * @param boolean $differentBillingAddress
     * @return Order
     */
    public function setDifferentBillingAddress($differentBillingAddress)
    {
        $this->differentBillingAddress = $differentBillingAddress;

        return $this;
    }

    /**
     * Get differentBillingAddress
     *
     * @return boolean 
     */
    public function getDifferentBillingAddress()
    {
        return $this->differentBillingAddress;
    }

    /**
     * @return bool
     */
    public function isDifferentBillingAddress()
    {
        return !!$this->getDifferentBillingAddress();
    }

    /**
     * Set orderedAt
     *
     * @param \DateTime $orderedAt
     * @return Order
     */
    public function setOrderedAt($orderedAt)
    {
        $this->orderedAt = $orderedAt;

        return $this;
    }

    /**
     * Get orderedAt
     *
     * @return \DateTime 
     */
    public function getOrderedAt()
    {
        return $this->orderedAt;
    }

    /**
     * Set payment
     *
     * @param PaymentInterface $payment
     * @return Order
     */
    public function setPayment(PaymentInterface $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return PaymentInterface
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set shipment
     *
     * @param ShipmentInterface $shipment
     * @return Order
     */
    public function setShipment(ShipmentInterface $shipment = null)
    {
        $this->shipment = $shipment;

        return $this;
    }

    /**
     * Get shipment
     *
     * @return ShipmentInterface
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * Add promotions
     *
     * @param PromotionInterface $promotions
     * @return Order
     */
    public function addPromotion(PromotionInterface $promotions)
    {
        $this->promotions[] = $promotions;

        return $this;
    }

    /**
     * Remove promotions
     *
     * @param PromotionInterface $promotions
     */
    public function removePromotion(PromotionInterface $promotions)
    {
        $this->promotions->removeElement($promotions);
    }

    /**
     * Get promotions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * @inheritdoc
     */
    public function getPromotionSubjectTotal()
    {
        $this->getItemsTotal();
    }

    /**
     * @inheritdoc
     */
    public function hasPromotion(PromotionInterface $promotion)
    {
       return $this->promotions->contains($promotion);
    }

    /**
     * @inheritdoc
     */
    public function getPromotionSubjectCount()
    {
        return $this->getTotalQuantity();
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user
     *
     * @param UserInterface $user
     * @return Order
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
