<?php
/**
 * ConfirmMailer.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;

use Enhavo\Bundle\ShopBundle\Document\GeneratorInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

class ConfirmMailer extends AbstractMailer
{
    public function sendMail(OrderInterface $order)
    {
        $body = $this->render($this->template, [
            'order' => $order,
            'translationDomain' => $this->translationDomain
        ]);

        $attach = new \Swift_Attachment();
        $attach->setFilename('bill.pdf');
        $attach->setContentType('application/pdf');
        $attach->setBody($this->getBillingGenerator()->generate(
            $order,
            $this->getBillingGeneratorOptions()
        ));

        $message = $this->createMessage();
        $message->addFrom($this->from, $this->senderName);
        $message->setSubject($this->getTranslator()->trans($this->subject, [], $this->translationDomain));
        $message->addTo($order->getCustomerEmail());
        $message->setBody($body, 'text/html');
        $message->attach($attach);

        $this->send($message);
    }

    /**
     * @return GeneratorInterface
     */
    protected function getBillingGenerator()
    {
        $generatorConfig = $this->container->getParameter('enhavo_shop.document.billing.generator');
        /** @var GeneratorInterface $generator */
        $generator = $this->container->get($generatorConfig);
        return $generator;
    }

    protected function getBillingGeneratorOptions()
    {
        return $this->container->getParameter('enhavo_shop.document.billing.options');
    }
}
