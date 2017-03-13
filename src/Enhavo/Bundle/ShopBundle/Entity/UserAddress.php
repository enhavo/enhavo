<?php
/**
 * User.php
 *
 * @since 12/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class UserAddress implements ResourceInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $user;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return boolean
     */
    public function isDifferentBillingAddress()
    {
        return $this->differentBillingAddress;
    }

    /**
     * @param boolean $differentBillingAddress
     */
    public function setDifferentBillingAddress($differentBillingAddress)
    {
        $this->differentBillingAddress = $differentBillingAddress;
    }
}