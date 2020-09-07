<?php
/**
 * DoubleOptInStrategyType.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Strategy\AbstractStrategyType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class DoubleOptInStrategyType extends AbstractStrategyType
{
    /** @var NewsletterManager */
    private $newsletterManager;

    /** @var RepositoryInterface */
    private $bufferRepository;

    /** @var RouterInterface */
    private $router;

    /**
     * DoubleOptInStrategyType constructor.
     * @param NewsletterManager $newsletterManager
     * @param RepositoryInterface $bufferRepository
     * @param RouterInterface $router
     */
    public function __construct(NewsletterManager $newsletterManager, RepositoryInterface $bufferRepository, RouterInterface $router)
    {
        $this->newsletterManager = $newsletterManager;
        $this->bufferRepository = $bufferRepository;
        $this->router = $router;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $subscriber->setCreatedAt(new \DateTime());
        $this->setToken($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifySubscriber($subscriber, $options);

        return 'subscriber.form.message.double_opt_in';
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->bufferRepository->delete($subscriber);
        $this->getStorage()->saveSubscriber($subscriber);
        $this->notifyAdmin($subscriber, $options);
        $this->confirmSubscriber($subscriber, $options);
    }

    private function notifySubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify']) {
            $link = $this->router->generate($options['activate_route'], array_merge($options['activate_route_parameters'], [
                'token' => $subscriber->getToken(),
                'type' => $subscriber->getType()
            ]), UrlGeneratorInterface::ABSOLUTE_URL);

            $template = $options['template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = '';//$this->getSubject();

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber,
                'link' => $link
            ]);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function confirmSubscriber(SubscriberInterface $subscriber, array $options)
    {
        if ($options['confirm']) {
            // TODO add unsubscribe/change subscription link
            $template = $options['confirmation_template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $subject = '';//$this->getSubject();

            $message = $this->newsletterManager->createMessage($from, $senderName, $subscriber->getEmail(), $subject, $template, [
                'subscriber' => $subscriber
            ]);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function notifyAdmin(SubscriberInterface $subscriber, array $options)
    {
        if ($options['notify_admin']) {
            $template = $options['admin_template'];
            $from = $options['from'];
            $senderName = $options['sender_name'];
            $to = $options['admin_email'];
            $subject = $this->getAdminSubject($options);

            $message = $this->newsletterManager->createMessage($from, $senderName, $to, $subject, $template, [
                'subscriber' => $subscriber
            ]);
            $this->newsletterManager->sendMessage($message);
        }
    }

    private function getAdminSubject(array $options)
    {
        $subject = $options['admin_subject'];
        $translationDomain = $options['translation_domain'];

        return '';//$this->translator->trans($subject, [], $translationDomain);
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        if ($options['check_exists']) {
            if ($this->bufferRepository->find($subscriber)) {
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
        $subscriber = $this->bufferRepository->findOneByEmail($subscriber->getEmail());

        if (!$subscriber->isActive()) {
            $this->setToken($subscriber);
            $this->notifySubscriber($subscriber, $subscriber->getType());

            return 'subscriber.form.error.sent_again';
        }
        return 'subscriber.form.error.exists';
    }

    public function getActivationTemplate(array $options)
    {
        return $options['activation_template'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'notify_admin' => false,
            'activation_template' => 'EnhavoNewsletterBundle:Subscriber:activate.html.twig',
            'admin_subject' => 'subscriber.mail.admin.subject',
            'template' => 'EnhavoNewsletterBundle:Subscriber:Email/double-opt-in.html.twig',
            'confirmation_template' => 'EnhavoNewsletterBundle:Subscriber:Email/confirmation.html.twig',
            'admin_template' => 'EnhavoNewsletterBundle:Subscriber:Email/notify-admin.html.twig',
            'activate_route' => 'enhavo_newsletter_subscribe_activate'
        ]);
    }

    public static function getName(): ?string
    {
        return 'double_opt_in';
    }
}
