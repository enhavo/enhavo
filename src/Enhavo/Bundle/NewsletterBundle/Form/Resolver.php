<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 09.12.16
 * Time: 14:40
 */

namespace Enhavo\Bundle\NewsletterBundle\Form;


use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use ProjectBundle\Entity\Subscriber;

class Resolver
{
    /**
     * @var array
     */
    private $formConfig;

    /**
     * @var array
     */
    private $defaultGroups;

    /**
     * Resolver constructor.
     *
     * @param $formConfig
     * @param $defaultGroups
     */
    public function __construct($formConfig, $defaultGroups)
    {
        $this->formConfig = $formConfig;
        $this->defaultGroups = $defaultGroups;
    }

    public function resolveType($formType)
    {
        if (key_exists($formType, $this->formConfig)) {
            return $this->formConfig[$formType]['type'];
        }
        return null;
    }

    public function resolveTemplate($formType)
    {
        if (key_exists($formType, $this->formConfig)) {
            return $this->formConfig[$formType]['template'];
        }
        return null;
    }

    public function resolveGroupNames($formType, $subscriber = null)
    {
        if (isset($this->formConfig[$formType]['storage']['options']['groups'])) {
            $formGroups = $this->formConfig[$formType]['storage']['options']['groups'];
        } else {
            $formGroups = [];
        }
        $groupsFromYml = $this->mergeArrays($this->defaultGroups, $formGroups);

        /** @var $subscriber SubscriberInterface */
        $groupNamesFromSubscriber = [];
        if ($subscriber !== null) {
            $groupsFromSubscriber = $subscriber->getGroup();
            /** @var Group $group */
            foreach ($groupsFromSubscriber as $group){
                $groupNamesFromSubscriber[] = $group->getName();
            }
        }

        return $this->mergeArrays($groupNamesFromSubscriber, $groupsFromYml);
    }

    protected function mergeArrays($array, $arrayToAdd)
    {
        foreach ($arrayToAdd as $toAdd) {
            if (!in_array($toAdd, $array)) {
                $array[] = $toAdd;
            }
        }
        return $array;
    }
}