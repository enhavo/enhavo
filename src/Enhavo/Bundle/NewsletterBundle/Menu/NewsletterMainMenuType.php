<?php
/**
 * NewsletterMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
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
                    'type' => 'newsletter_newsletter'
                ],
                'newsletter_group' => [
                    'type' => 'newsletter_group'
                ],
                'pending_subscribers' => [
                    'type' => 'newsletter_pending'
                ],
            ]
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
