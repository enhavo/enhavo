<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LinkMenuType extends AbstractMenuType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $url = $this->router->generate($options['route'], $options['route_parameters']);
        $data->add([
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'url' => $url,
            'icon' => $options['icon'],
            'class' => $options['class'],
            'frame' => $options['frame'],
            'clear' => $options['clear'],
            'info' => $this->translator->trans($options['info'], [], $options['translation_domain']),
            'notification' => [
                'class' => $options['notification_class'],
                'label' => $this->translator->trans($options['notification_label'], [], $options['translation_domain']),
                'icon' => $options['notification_icon'],
                'info' => $this->translator->trans($options['notification_info'], [], $options['translation_domain']),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'menu-item',
            'model' => 'BaseMenuItem',
            'translation_domain' => null,
            'icon' => null,
            'class' => null,
            'info' => null,
            'notification_class' => null,
            'notification_label' => null,
            'notification_icon' => null,
            'notification_info' => null,
            'route_parameters' => [],
            'permission' => null,
            'enabled' => true,
            'key' => null,
            'frame' => null,
            'clear' => true,
        ]);

        $resolver->setRequired([
            'label',
            'route'
        ]);
    }

    public static function getName(): ?string
    {
        return 'link';
    }
}
