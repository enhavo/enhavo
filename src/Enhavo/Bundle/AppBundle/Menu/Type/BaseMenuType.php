<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Menu\MenuTypeInterface;
use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseMenuType extends AbstractType implements MenuTypeInterface
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
            'mainUrl' => $this->generateMainUrl($url, $options),
            'icon' => $options['icon'],
            'component' => $options['component'],
            'model' => $options['model'],
            'class' => $options['class'],
            'info' => $this->translator->trans($options['info'], [], $options['translation_domain']),
            'notification' => [
                'class' => $options['notification_class'],
                'label' => $this->translator->trans($options['notification_label'], [], $options['translation_domain']),
                'icon' => $options['notification_icon'],
                'info' => $this->translator->trans($options['notification_info'], [], $options['translation_domain']),
            ],
        ]);
    }

    private function generateMainUrl($url, $options)
    {
        $state = StateEncoder::encode([
            'views' => [['url' => $url, 'id' => 2]],
            'storage' => [['key' => 'menu-active-key', 'value' => $options['key']]]
        ]);

        return $this->router->generate('enhavo_app_admin_index', [
            'state' => $state
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
            'role' => null,
            'enabled' => true,
            'key' => null,
        ]);

        $resolver->setRequired([
            'label',
            'route'
        ]);
    }

    public function getPermission(array $options): mixed
    {
        return $options['role'];
    }

    public function isEnabled(array $options): bool
    {
        return $options['enabled'];
    }

    public static function getName(): ?string
    {
        return 'base';
    }
}
