<?php
/**
 * NotificationMailer.php
 *
 * @since 18/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Swift_Mailer;
use Symfony\Component\Templating\EngineInterface;

class NotificationMailer extends AbstractMailer
{
    /**
     * @var string
     */
    protected $to;

    /**
     * @var boolean
     */
    protected $notify;

    public function __construct(Swift_Mailer $mailer, EngineInterface $templateEngine, $config)
    {
        parent::__construct($mailer, $templateEngine, $config);
        $this->to =  $config['to'];
        $this->notify =  $config['notify'];
    }

    public function sendMail(OrderInterface $order)
    {
        if(!$this->notify) {
            return;
        }

        $body = $this->render($this->template, [
            'order' => $order,
            'translationDomain' => $this->translationDomain
        ]);

        $message = $this->createMessage();
        $message->addFrom($this->from);
        $message->setSubject($this->getTranslator()->trans($this->subject, [], $this->translationDomain));
        $message->addTo($this->to);
        $message->setBody($body, 'text/html');

        $this->send($message);
    }
}