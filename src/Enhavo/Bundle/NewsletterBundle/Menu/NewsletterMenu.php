<?php
/**
 * NewsletterMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'inbox',
            'label' => 'newsletter.label.newsletter',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_newsletter_index',
            'role' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_INDEX'
        ]);
    }

    public function getType()
    {
        return 'newsletter_newsletter';
    }
}