<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            'frame_key' => 'stats-view',
            'target' => '_frame',
            'append_id' => true,
        ]);
    }

    public function isEnabled(array $options, $resource = null): bool
    {
        if (!$resource instanceof NewsletterInterface) {
            return false;
        } elseif (NewsletterInterface::STATE_CREATED === $resource->getState()) {
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
