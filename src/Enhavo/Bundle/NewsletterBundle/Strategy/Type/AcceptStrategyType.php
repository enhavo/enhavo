<?php
/**
 * AcceptStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Form\Resolver;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Enhavo\Bundle\NewsletterBundle\Subscribtion\SubscribtionManager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AcceptStrategyType extends AbstractStrategyType
{
    const DEFAULT_PROVIDER = 'local';

    /** @var SubscribtionManager */
    private $subscriberManager;
    /**
     * @var Resolver
     */
    private $formResolver;

    /** @var RouterInterface */
    private $router;

    /** @var TranslatorInterface */
    private $translator;


    /**
     * AcceptStrategyType constructor.
     * @param SubscribtionManager $subscriberManager
     * @param Resolver $formResolver
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(SubscribtionManager $subscriberManager, Resolver $formResolver, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->subscriberManager = $subscriberManager;
        $this->formResolver = $formResolver;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options = [])
    {
        $subscriber->setCreatedAt(new \DateTime());
        $subscriber->setActive(false);
        $this->setToken($subscriber);
        $this->subscriberManager->saveSubscriber($subscriber, self::DEFAULT_PROVIDER);
        $this->notifyAdmin($subscriber);

        return 'subscriber.form.message.accept';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, $type = null)
    {
        $subscriber->setActive(true);
        $subscriber->setToken(null);
        $this->subscriberManager->saveSubscriber($subscriber, self::DEFAULT_PROVIDER);
        $this->subscriberManager->saveSubscriber($subscriber, $type);
        $this->notifySubscriber($subscriber);
    }

    private function notifySubscriber(SubscriberInterface $subscriber)
    {
        $notify = $this->getTypeOption('notify', $subscriber->getType(), false);

        if($notify) {
            $template = $this->getTypeOption('template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/accept.html.twig');
            $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
            $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enhavo');

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
        $link = $this->router->generate('enhavo_newsletter_subscribe_accept', [
            'token' => $subscriber->getToken(),
            'type' => $subscriber->getType()
        ], true);

        $template = $this->getTypeOption('admin_template', $subscriber->getType(), 'EnhavoNewsletterBundle:Subscriber:Email/accept-admin.html.twig');
        $from = $this->getTypeOption('from', $subscriber->getType(), 'no-reply@enhavo.com');
        $senderName = $this->getTypeOption('sender_name', $subscriber->getType(), 'enhavo');
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

        return $this->translator->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber, array $options = []): bool
    {
        $checkExists = $this->getTypeOption('check_exists', $subscriber->getType(), false);

        if($checkExists) {
            return $this->subscriberManager->exists($subscriber, $subscriber->getType());
        }
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options = [])
    {
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate($type)
    {
        return $this->getTypeOption('activation_template', $type, 'EnhavoNewsletterBundle:Subscriber:accept.html.twig');
    }

    public static function getName(): ?string
    {
        return 'accept';
    }

}
