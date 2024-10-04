<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatsActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'open-action',
            'label' => 'newsletter.action.stats.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'assessment',
            'route' => 'enhavo_newsletter_admin_newsletter_stats',
            'view_key' => 'stats-view',
            'target' => '_view',
            'append_id' => true
        ]);
    }

    public function isEnabled(array $options, $resource = null): bool
    {
        if (!$resource instanceof NewsletterInterface) {
            return false;
        } elseif($resource->getState() === NewsletterInterface::STATE_CREATED) {
            return false;
        }
        return $this->parent->isEnabled($options, $resource);
    }

    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'newsletter_stats';
    }
}
