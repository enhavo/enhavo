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
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PendingMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'add_box',
            'label' => 'subscriber.label.pending',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_admin_pending_subscriber_index',
            'permission' => 'ROLE_ENHAVO_NEWSLETTER_PENDING_SUBSCRIBER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_pending';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
