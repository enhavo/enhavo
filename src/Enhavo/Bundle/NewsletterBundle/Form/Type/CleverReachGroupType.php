<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CleverReachGroupType extends AbstractType
{
    /** @var SubscriptionManager */
    private $subscriptionManager;

    /**
     * CleverReachGroupType constructor.
     */
    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'subscriber.form.label.groups',
            'groups' => null,
            'multiple' => true,
            'expanded' => true,
        ]);
        $resolver->setRequired('subscription');

        $resolver->setDefault('choice_loader', function (Options $options) {
            return new CallbackChoiceLoader(function () use ($options) {
                $choices = [];

                $subscription = $this->subscriptionManager->getSubscription($options['subscription']);
                $storage = $subscription->getStrategy()->getStorage();
                if ($options['groups']) {
                    foreach ($options['groups'] as $group) {
                        $remoteGroup = $storage->getGroup($group);
                        $choices[$remoteGroup->getName()] = $remoteGroup->getCode();
                    }
                } else {
                    $groups = $storage->getGroups();
                    foreach ($groups as $group) {
                        $choices[$group->getName()] = $group->getCode();
                    }
                }

                return $choices;
            });
        });
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_clever_reach_group';
    }
}
