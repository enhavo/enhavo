<?php
/**
 * OrderInitProcessor.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OrderInitProcessor implements ProcessorInterface
{
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenGeneratorInterface $tokenGenerator, TokenStorage $tokenStorage)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_CART);
        $order->setUser($this->getUser());

        if($order->getToken() === null) {
            $order->setToken($this->tokenGenerator->generate(40));
        }
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if($token) {
            $user = $token->getUser();
            if($user instanceof UserInterface) {
                return $user;
            }
        }
        return null;
    }
}
