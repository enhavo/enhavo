<?php
/**
 * SubscriberMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'add_box',
            'label' => 'subscriber.label.subscriber',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_local_subscriber_index',
            'role' => 'ROLE_ENHAVO_NEWSLETTER_LOCAL_SUBSCRIBER_INDEX',
        ]);
    }

    public function getType()
    {
        return 'newsletter_subscriber';
    }
}
