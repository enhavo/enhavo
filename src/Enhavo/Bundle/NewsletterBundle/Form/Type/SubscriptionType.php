<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    /** @var SubscriptionManager */
    private $subscriptionManager;

    /**
     * SubscriptionType constructor.
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $subscriptions = $this->subscriptionManager->getSubscriptions();
        $choices = [];
        foreach ($subscriptions as $subscription) {
            $choices[$subscription->getName()] = $subscription->getName();
        }

        $resolver->setDefaults([
            'label' => 'subscriber.form.label.subscription',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'multiple' => false,
            'choices' => $choices,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_subscription';
    }
}
