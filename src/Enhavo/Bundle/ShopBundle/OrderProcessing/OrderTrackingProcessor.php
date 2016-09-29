<?php
/**
 * OrderTrackingProcessor.php
 *
 * @since 29/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Mailer\MailerInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

class OrderTrackingProcessor implements ProcessorInterface
{
    /**
     * @var MailerInterface
     */
    protected $trackingMailer;

    public function __construct(
        MailerInterface $trackingMailer
    ) {
        $this->trackingMailer = $trackingMailer;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order)
    {
        if($order->getShippingState() === ShipmentInterface::STATE_SHIPPED && !$order->isTrackingMail()) {
            $this->trackingMailer->sendMail($order);
            $order->setTrackingMail(true);
        }
    }
}