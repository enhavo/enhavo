<?php
/**
 * NewsletterMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\NewsletterBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\ListMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterMainMenu extends ListMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'newsletter',
            'label' => 'newsletter.label.newsletter',
            'translationDomain' => 'EnhavoNewsletterBundle',
            'menu' => [
                'newsletter' => [
                    'type' => 'newsletter_newsletter'
                ],
                'subscriber' => [
                    'type' => 'newsletter_subscriber'
                ],
            ]
        ]);
    }

    public function getType()
    {
        return 'newsletter';
    }
}