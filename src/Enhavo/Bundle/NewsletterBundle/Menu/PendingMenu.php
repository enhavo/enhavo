<?php
/**
 * SubscriberMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PendingMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'add_box',
            'label' => 'subscriber.label.pending',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_pending_subscriber_index',
            'role' => 'ROLE_ENHAVO_NEWSLETTER_PENDING_SUBSCRIBER_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_pending';
    }
}
