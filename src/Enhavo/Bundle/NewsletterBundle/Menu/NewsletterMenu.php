<?php
/**
 * NewsletterMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'inbox',
            'label' => 'newsletter.label.newsletter',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'route' => 'enhavo_newsletter_admin_newsletter_index',
            'role' => 'ROLE_ENHAVO_NEWSLETTER_NEWSLETTER_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'newsletter_newsletter';
    }
}
