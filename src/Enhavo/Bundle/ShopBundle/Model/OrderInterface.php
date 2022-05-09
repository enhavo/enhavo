<?php

namespace Enhavo\Bundle\ShopBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Order\Model\OrderInterface as SyliusOrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

/**
 * OrderInterface.php
 *
 * @since 15/08/16
 * @author gseidel
 */
interface OrderInterface extends SyliusOrderInterface, PromotionSubjectInterface, AddressSubjectInterface
{
    public function setPaymentState(?string $paymentState): void;
    public function getPaymentState(): ?string;
    public function setShippingState(?string $shippingState);
    public function getShippingState();
    public function setPromotionCoupon(PromotionCouponInterface $promotionCoupon = null);
    public function getPromotionCoupon();
    public function addPayment(PaymentInterface $payment);
    public function removePayment(PaymentInterface $payment);
    public function getPayments(): Collection|array;
    public function addShipment(ShipmentInterface $shipment);
    public function removeShipment(ShipmentInterface $shipment);
    public function getShipments(): Collection|array;
    public function hasShipments(): bool;
    public function getUser();
    public function setUser(UserInterface $user = null);
    public function getEmail();
    public function getShippingTotal();
    public function getShippingTax();
    public function getShippingPrice();
    public function getDiscountTotal();
    public function getTaxTotal();
    public function getUnitTotal();
    public function getUnitPriceTotal();
    public function getCustomerEmail();
    public function getToken();
    public function setToken($token);
    public function isTrackingMail();
    public function setTrackingMail($trackingMail);
    public function isFreeShipping();
    public function getUnitTaxTotal();
    public function isShippable();
    public function getItemUnits();

}
