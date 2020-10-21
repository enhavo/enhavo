<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Model\GroupAwareInterface;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PendingSubscriberType extends AbstractType
{
    /** @var SubscriptionManager */
    private $subscriptionManager;

    /** @var RepositoryInterface */
    private $groupRepository;

    /**
     * PendingSubscriberType constructor.
     * @param SubscriptionManager $subscriptionManager
     * @param RepositoryInterface $groupRepository
     */
    public function __construct(SubscriptionManager $subscriptionManager, RepositoryInterface $groupRepository)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->groupRepository = $groupRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, array(
            'label' => 'subscriber.form.label.email',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ));

        $builder->add('subscription', SubscriptionType::class, [
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            /** @var PendingSubscriber $pendingSubscriber */
            $pendingSubscriber = $event->getData();

            $subscription = $this->subscriptionManager->getSubscription($pendingSubscriber->getSubscription());
            $formConfig = $subscription->getFormConfig();

            if ($pendingSubscriber->getData() instanceof GroupAwareInterface) {
                /** @var GroupAwareInterface $data */
                $data = $pendingSubscriber->getData();
                /** @var Group $group */
                foreach ($data->getGroups() as $group) {
                    $data->removeGroup($group);
                    $group = $this->groupRepository->find($group->getId());
                    $data->addGroup($group);
                }
            }

            $form->add('data', $formConfig['class'], [
                'data_class' => $subscription->getModel(),
                'label' => 'subscriber.form.label.data',
                'translation_domain' => 'EnhavoNewsletterBundle',
                'constraints' => []
            ]);
        });

    }

    public function resolveOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PendingSubscriber::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_subscriber';
    }
}
