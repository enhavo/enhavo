<?php
/**
 * AcceptStrategy.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\LocalStorage;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\StorageResolver;

class AcceptStrategy extends AbstractStrategy
{
    /**
     * @var LocalStorage
     */
    private $localStorage;

    /**
     * @var StorageResolver
     */
    private $storageResolver;

    /**
     * @var Resolver
     */
    private $formResolver;

    /**
     * AcceptStrategy constructor.
     * @param array $options
     * @param array $typeOptions
     * @param LocalStorage $localStorage
     * @param StorageResolver $storageResolver
     * @param Resolver $formResolver
     */
    public function __construct($options, $typeOptions, LocalStorage $localStorage, StorageResolver $storageResolver, Resolver $formResolver)
    {
        parent::__construct($options, $typeOptions);
        $this->localStorage = $localStorage;
        $this->storageResolver = $storageResolver;
        $this->formResolver = $formResolver;
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

    public function activateSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $subscriber->setActive(true);
        $subscriber->setToken(null);
        $this->localStorage->saveSubscriber($subscriber);
        $this->getSubscriberManager()->saveSubscriber($subscriber, $type);
        $this->notifySubscriber($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('notify', $subscriber->getType(), false);

        if($notify) {
            $template = $this->getTypeOption('template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/accept.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enahvo');

            $message = new \Swift_Message();
            $message->setSubject($this->getSubject())
                ->setFrom($from, $senderName)
                ->setTo($subscriber->getEmail())
                ->setBody($this->renderTemplate($template, [
                    'subscriber' => $subscriber
                ]), 'text/html');
            $this->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber)
    {
        $link = $this->getRouter()->generate('enhavo_newsletter_subscribe_accept', [
            'token' => $subscriber->getToken(),
            'type' => $subscriber->getType()
        ], true);

        $template = $this->getTypeOption('admin_template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/accept-admin.html.twig');
        $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
        $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enahvo');
        $to = $this->getTypeOption('admin_email', $subscriber->getType(), 'no-reply@enhavo.com');

        $message = new \Swift_Message();
        $message->setSubject($this->getAdminSubject($subscriber->getType()))
            ->setFrom($from, $senderName)
            ->setTo($to)
            ->setBody($this->renderTemplate($template, [
                'subscriber' => $subscriber,
                'link' => $link
            ]), 'text/html');
        $this->sendMessage($message);
    }

    private function getAdminSubject($type)
    {
        $subject = $this->getTypeOption('admin_subject', $type, 'Newsletter Subscription');
        $translationDomain = $this->getTypeOption('admin_translation_domain', $type, null);

        return $this->container->get('translator')->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber)
    {
        $checkExists = $this->getTypeOption('check_exists', $subscriber->getType(), false);

        if($checkExists) {
            /** @var StorageInterface $storage */
            $storage = $this->storageResolver->resolve($subscriber->getType());
            return $storage->exists($subscriber);
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber)
    {
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate($type)
    {
        return $this->getTypeOption('activation_template', $type, 'EnhavoNewsletterBundle:Subscriber:accept.html.twig');
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
