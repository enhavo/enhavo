<?php
/**
 * NotifyStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;


use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;

class NotifyStrategy extends AbstractStrategy
{
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActivatedAt(new \DateTime());
        $subscriber->setActive(true);
        $this->getSubscriberManager()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber);
        $this->notifyAdmin($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        if($this->getOption('notify', $this->options, true)) {
            $template = $this->getOption('template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/notify.html.twig');
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
        if($this->getOption('admin_notify', $this->options, false)) {
            $template = $this->getOption('admin_template', $this->options, 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig');
            $message = \Swift_Message::newInstance()
                ->setSubject($this->getAdminSubject())
                ->setFrom($this->getOption('from', $this->options, 'no-reply@enhavo.com'))
                ->setTo($this->getOption('admin_email', $this->options, 'no-reply@enhavo.com'))
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function getAdminSubject()
    {
        $subject = $this->getOption('admin_subject', $this->options, 'Newsletter Subscription');
        $translationDomain = $this->getOption('admin_translation_domain', $this->options, null);
        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        return $this->getSubscriberManager()->getStorage()->exists($subscriber);
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return 'already exists';
    }

    public function getType()
    {
        return 'notify';
    }
}