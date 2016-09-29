<?php
/**
 * TrackingMailer.php
 *
 * @since 29/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class TrackingMailer extends AbstractMailer
{
    public function sendMail(OrderInterface $order)
    {
        $body = $this->render($this->template, [
            'order' => $order,
            'translationDomain' => $this->translationDomain
        ]);

        $message = $this->createMessage();
        $message->addFrom($this->from);
        $message->setSubject($this->getTranslator()->trans($this->subject, [], $this->translationDomain));
        $message->addTo($order->getCustomerEmail());
        $message->setBody($body, 'text/html');

        $this->send($message);
    }
}