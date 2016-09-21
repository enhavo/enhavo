<?php
/**
 * DoubleOptInStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\LocalStorage;

class DoubleOptInStrategy extends AbstractStrategy
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    public function __construct($options, LocalStorage $localStorage)
    {
        parent::__construct($options);
        $this->localStorage = $localStorage;
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $this->getSubscriberManager()->saveSubscriber($subscriber);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        return $this->localStorage->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        // TODO: Implement handleExists() method.
    }

    public function sendNotification()
    {
        $em = $this->em;
        $subscriberRepository = $em->getRepository('EnhavoNewsletterBundle:Subscriber');
        $subscriber = $subscriberRepository->findOneBy(array('email' => $value));
        if($subscriber != null) {
            if($subscriber->getActive() == true) {
                $this->context->buildViolation($constraint->messageEmailInUse)
                    ->addViolation();
            } else {
                $email = $subscriber->getEmail();
                $code = $subscriber->getToken();

                $router = $this->container->get('router');
                $link = $router->generate('enhavo_newsletter_subscriber_activation', array('code' => $code), true);
                $text = $this->container->get('templating')->render($this->subscriber['template'], array(
                    "link" => $link
                ));

                $message = \Swift_Message::newInstance()
                    ->setSubject($this->subscriber['subject'])
                    ->setFrom($this->subscriber['send_from'])
                    ->setTo($email)
                    ->setBody($text);

                $this->container->get('mailer')->send($message);
                $this->context->buildViolation($constraint->messageMailAgain)
                    ->addViolation();
            }
        }
    }

    public function getType()
    {
        return 'double_opt_in';
    }
}