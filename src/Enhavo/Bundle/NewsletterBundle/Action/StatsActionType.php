<?php

namespace Enhavo\Bundle\NewsletterBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatsActionType extends OpenActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'open-action',
            'label' => 'newsletter.action.stats.label',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'icon' => 'assessment',
            'route' => 'enhavo_newsletter_newsletter_stats',
            'view_key' => 'stats-view',
            'target' => '_view',
            'append_id' => true
        ]);
    }

    public function isHidden(array $options, $resource = null)
    {
        if(!$resource instanceof NewsletterInterface) {
            return true;
        } elseif($resource->getState() === NewsletterInterface::STATE_CREATED) {
            return true;
        }
        return parent::isHidden($options, $resource);
    }

    public function getType()
    {
        return 'newsletter_stats';
    }
}
