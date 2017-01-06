<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 09.12.16
 * Time: 14:40
 */

namespace Enhavo\Bundle\NewsletterBundle\Form;


use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Group\GroupManager;
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
     * @var GroupManager
     */
    private $groupManager;

    /**
     * Resolver constructor.
     *
     * @param $formConfig
     * @param $defaultGroups
     * @param GroupManager $groupManager
     */
    public function __construct($formConfig, $defaultGroups, GroupManager $groupManager)
    {
        $this->formConfig = $formConfig;
        $this->defaultGroups = $defaultGroups;
        $this->groupManager = $groupManager;
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

    /**
     * Return groups by form type
     *
     * @param string $formType
     *
     * @return Group[]
     */
    public function resolveGroups($formType)
    {
        $formGroups = null;
        if (isset($this->formConfig[$formType]['default_groups'])) {
            $formGroups = $this->formConfig[$formType]['default_groups'];
        }

        if(is_array($formGroups)) {
            $groups = $formGroups;
        } else {
            $groups = $this->defaultGroups;
        }

        return $this->groupManager->getGroupsByCodes($groups);
    }
}