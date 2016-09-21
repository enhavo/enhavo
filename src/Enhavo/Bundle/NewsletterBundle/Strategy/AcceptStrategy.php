<?php
/**
 * AcceptStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\LocalStorage;

class AcceptStrategy extends AbstractStrategy
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
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActive(false);
        $this->setToken($subscriber);
        $this->localStorage->saveSubscriber($subscriber);
        $this->notifyAdmin($subscriber);
        return 'subscriber.form.message.accept';
    }

    public function activateSubscriber(SubscriberInterface $subscriber)
    {
        $subscriber->setActive(true);
        $subscriber->setToken(null);
        $this->localStorage->saveSubscriber($subscriber);
        $this->getSubscriberManager()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        if($this->getOption('notify', $this->options, false)) {
            $template = $this->getOption('template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/accept.html.twig');
            $message = \Swift_Message::newInstance()
                ->setSubject($this->getSubject())
                ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber)
    {
        $link = $this->getRouter()->generate('enhavo_newsletter_subscribe_accept', array('token' => $subscriber->getToken()), true);
        $template = $this->getOption('admin_template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/accept-admin.html.twig');
        $message = \Swift_Message::newInstance()
            ->setSubject($this->getAdminSubject())
            ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
            ->setTo($this->getOption('admin_email', $this->options, 'no-reply@enhavo.com'))
            ->setBody($this->renderTemplate($template, [
                'subscriber' => $subscriber,
                'link' => $link
            ]), 'text/html');
        $this->sendMessage($message);
    }

    private function getAdminSubject()
    {
        $subject = $this->getOption('admin_subject', $this->options, 'Newsletter Subscription');
        $translationDomain = $this->getOption('admin_translation_domain', $this->options, null);
        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        return $this->localStorage->exists($subscriber) || $this->getSubscriberManager()->getStorage()->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate()
    {
        return $this->getOption('activation_template', $this->options, 'EnhavoNewsletterBundle:Subscriber:accept.html.twig');
    }

    private function getRouter()
    {
        return $this->container->get('router');
    }

    public function getType()
    {
        return 'accept';
    }
}