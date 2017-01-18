<?php
/**
 * ConfirmMailer.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Document\BillingGenerator;

class ConfirmMailer extends AbstractMailer
{
    public function sendMail(OrderInterface $order)
    {
        $body = $this->render($this->template, [
            'order' => $order,
            'translationDomain' => $this->translationDomain
        ]);

        $attach = \Swift_Attachment::newInstance();
        $attach->setFilename('bill.pdf');
        $attach->setContentType('application/pdf');
        $attach->setBody($this->getBillingGenerator()->generate($order));

        $message = $this->createMessage();
        $message->addFrom($this->from, $this->senderName);
        $message->setSubject($this->getTranslator()->trans($this->subject, [], $this->translationDomain));
        $message->addTo($order->getCustomerEmail());
        $message->setBody($body, 'text/html');
        $message->attach($attach);

        $this->send($message);
    }

    /**
     * @return BillingGenerator
     */
    protected function getBillingGenerator()
    {
        return $this->container->get($this->container->getParameter('enhavo_shop.document.billing')['generator']);
    }
}