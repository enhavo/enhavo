<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AcceptStrategyType extends AbstractStrategyType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var PendingSubscriberManager */
    private $pendingManager;

    /** @var RouterInterface */
    private $router;

    /**
     * AcceptStrategyType constructor.
     */
    public function __construct(NewsletterManager $newsletterManager, PendingSubscriberManager $pendingManager, RouterInterface $router)
    {
        $this->newsletterManager = $newsletterManager;
        $this->pendingManager = $pendingManager;
        $this->router = $router;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->preAddSubscriber($subscriber);
        $subscriber->setCreatedAt(new \DateTime());
        $pending = $this->pendingManager->createFrom($subscriber);

        $this->pendingManager->save($pending);
        $subscriber->setConfirmationToken($pending->getConfirmationToken());
        $this->notifyAdmin($subscriber, $options);

        $this->postAddSubscriber($subscriber);

        return 'subscriber.form.message.accept';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->preActivateSubscriber($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->pendingManager->removeBy($subscriber->getEmail(), $subscriber->getSubscription());

        $this->notifySubscriber($subscriber, $options);
        $this->postActivateSubscriber($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = $this->trans($options['subject'], [], $options['translation_domain']);

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber,
            ], $options['content_type']);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber, array $options)
    {
        $link = $this->router->generate($options['activate_route'], array_merge($options['activate_route_parameters'], [
            'token' => $subscriber->getConfirmationToken(),
            'type' => $subscriber->getSubscription(),
        ]), UrlGeneratorInterface::ABSOLUTE_URL);

        $template = $options['admin_template'];
        $from = $options['from'];
        $senderName = $options['sender_name'];
        $to = $options['admin_email'];
        $subject = $this->trans($options['admin_subject'], [], $options['translation_domain']);

        $message = $this->newsletterManager->createMessage($from, $senderName, $to, $subject, $template, [
            'subscriber' => $subscriber,
            'link' => $link,
        ], $options['content_type']);
        $this->newsletterManager->sendMessage($message);
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        if ($options['check_exists']) {
            if ($this->pendingManager->findOneBy($subscriber->getEmail(), $subscriber->getSubscription())) {
                return true;
            }

            if ($this->getStorage()->exists($subscriber)) {
                return true;
            }
        }

        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return null;
    }

    public function getActivationTemplate(array $options): ?string
    {
        return $options['activation_template'];
    }

    public function getUnsubscribeTemplate(array $options): ?string
    {
        return $options['unsubscribe_template'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'activation_template' => 'theme/resource/subscriber/accept.html.twig',
            'unsubscribe_template' => 'theme/resource/subscriber/unsubscribe.html.twig',
            'admin_template' => 'mail/subscriber/accept-admin.html.twig',
            'template' => 'mail/subscriber/accept.html.twig',
            'activate_route' => 'enhavo_newsletter_subscribe_activate',
            'activate_route_parameters' => [],
        ]);
    }

    public static function getName(): ?string
    {
        return 'accept';
    }
}
