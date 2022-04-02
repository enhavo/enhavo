<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Order\Model\OrderInterface as SyliusOrderInterface;
use Sylius\Component\Promotion\Model\PromotionCoupon;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

/**
 * OrderInterface.php
 *
 * @since 15/08/16
 * @author gseidel
 */
interface OrderInterface extends SyliusOrderInterface, PromotionSubjectInterface
{
    /**
     * Set checkoutState
     *
     * @param string $checkoutState
     *
     * @return OrderInterface
     */
    public function setCheckoutState($checkoutState);

    /**
     * Get checkoutState
     *
     * @return string
     */
    public function getCheckoutState();

    /**
     * Set paymentState
     *
     * @param string $paymentState
     *
     * @return OrderInterface
     */
    public function setPaymentState($paymentState);

    /**
     * Get paymentState
     *
     * @return string
     */
    public function getPaymentState();

    /**
     * Set shippingState
     *
     * @param string $shippingState
     *
     * @return OrderInterface
     */
    public function setShippingState($shippingState);

    /**
     * Get shippingState
     *
     * @return string
     */
    public function getShippingState();

    /**
     * Set promotionCoupon
     *
     * @param PromotionCouponInterface $promotionCoupon
     *
     * @return OrderInterface
     */
    public function setPromotionCoupon(PromotionCouponInterface $promotionCoupon = null);

    /**
     * Get promotionCoupon
     *
     * @return PromotionCoupon
     */
    public function getPromotionCoupon();

    /**
     * Set shippingAddress
     *
     * @param AddressInterface $shippingAddress
     *
     * @return OrderInterface
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null);

    /**
     * Get shippingAddress
     *
     * @return AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set billingAddress
     *
     * @param AddressInterface $billingAddress
     *
     * @return OrderInterface
     */
    public function setBillingAddress(AddressInterface $billingAddress = null);

    /**
     * Get billingAddress
     *
     * @return AddressInterface
     */
    public function getBillingAddress();

    /**
     * Get Shipment
     *
     * @return ShipmentInterface
     */
    public function getShipment();

    /**
     * @param ShipmentInterface|null $shipment
     * @return mixed
     */
    public function setShipment(ShipmentInterface $shipment = null);

    /**
     * @return bool
     */
    public function isDifferentBillingAddress();

    /**
     * @return PaymentInterface
     */
    public function getPayment();

    /**
     * @return UserInterface|null
     */
    public function getUser();

    /**
     * @param UserInterface|null $user
     * @return mixed
     */
    public function setUser(UserInterface $user = null);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * Returns the amount of the shipping costs including shipping promotions and tax
     *
     * @return integer
     */
    public function getShippingTotal();

    /**
     * Returns the amount of the shipping tax
     *
     * @return integer
     */
    public function getShippingTax();

    /**
     * Returns the amount of the shipping costs including shipping promotions
     *
     * @return integer
     */
    public function getShippingPrice();

    /**
     * Return the total amount of all promotion discounts on that order excluding items and shipping promotions
     *
     * @return integer
     */
    public function getDiscountTotal();

    /**
     * Return the total amount of all taxes including the items
     *
     * @return integer
     */
    public function getTaxTotal();

    /**
     * Returns the total amount of all units including taxes but including promotion
     *
     * @return integer
     */
    public function getUnitTotal();

    /**
     *  Returns the total amount of all units excluding taxes but including promotion
     *
     * @return integer
     */
    public function getUnitPriceTotal();

    /**
     * Return email of customer
     *
     * @return mixed
     */
    public function getCustomerEmail();

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @param $orderedAt
     * @return mixed
     */
    public function setOrderedAt($orderedAt);

    /**
     * Get orderedAt
     *
     * @return \DateTime
     */
    public function getOrderedAt();

    /**
     * @return mixed
     */
    public function isTrackingMail();

    /**
     * @param boolean $trackingMail
     */
    public function setTrackingMail($trackingMail);

    /**
     * Returns true if shipping cost is 0
     *
     * @return boolean
     */
    public function isFreeShipping();

    /**
     * Return total tax of all units
     *
     * @return mixed
     */
    public function getUnitTaxTotal();

    /**
     * Set differentBillingAddress
     *
     * @param boolean $differentBillingAddress
     * @return OrderInterface
     */
    public function setDifferentBillingAddress($differentBillingAddress);

    /**
     * Get differentBillingAddress
     *
     * @return boolean
     */
    public function getDifferentBillingAddress();
}
