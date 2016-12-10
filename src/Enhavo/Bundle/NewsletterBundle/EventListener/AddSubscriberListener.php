<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 15:11
 */

namespace Enhavo\Bundle\NewsletterBundle\EventListener;


use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Form\Resolver;

class AddSubscriberListener
{
    /**
     * @var array
     */
    private $formTypesFromConfig;

    /**
     * @var array
     */
    private $defaultGroups;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($formTypesFromConfig, $defaultGroups, $em)
    {
        $this->formTypesFromConfig = $formTypesFromConfig;
        $this->defaultGroups = $defaultGroups;
        $this->em = $em;
    }

    public function onAddSubscriber(SubscriberEvent $event)
    {
        $subscriber = $event->getSubscriber();

        foreach ($this->formTypesFromConfig as $formTypeName => $formTypeConfig) {
            if ($event->getType() != $formTypeName) {
                continue;
            }

            if (isset($formTypeConfig['storage']['options']['groups'])) {
                $groupsFromConfig = $formTypeConfig['storage']['options']['groups'];
            } else {
                $groupsFromConfig = [];
            }

            foreach ($this->defaultGroups as $defaultGroupToAdd) {
                if (!in_array($defaultGroupToAdd, $groupsFromConfig)) {
                    $groupsFromConfig[] = $defaultGroupToAdd;
                }
            }

            foreach ($groupsFromConfig as $groupToAddFromConfig) {
                $group = $this->em->getRepository(Group::class)->findOneBy(['name' => $groupToAddFromConfig]);
                if ($group === null) {
                    $group = new Group();
                    $group->setName($groupToAddFromConfig);
                    $this->em->persist($group);
                }

                $groupsSubscriberIsIn = $subscriber->getGroup();

                if (!$groupsSubscriberIsIn->contains($group)) {
                    $subscriber->addGroup($group);
                    $this->em->persist($subscriber);
                }
            }
            $this->em->flush();
        }

    }
}