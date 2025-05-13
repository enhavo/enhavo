<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\ListMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterMainMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'markunread',
            'label' => 'newsletter.label.newsletter',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'items' => [
                'newsletter_newsletter' => [
                    'type' => 'newsletter_newsletter',
                ],
                'newsletter_group' => [
                    'type' => 'newsletter_group',
                ],
                'pending_subscribers' => [
                    'type' => 'newsletter_pending',
                ],
            ],
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter';
    }

    public static function getParentType(): ?string
    {
        return ListMenuType::class;
    }
}
