<?php
/**
 * BaseMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseMenu extends AbstractMenu
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * BaseMenu constructor.
     *
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function createViewData(array $options)
    {
        $url = $this->router->generate($options['route'], $options['route_parameters']);
        $data = [
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'url' => $url,
            'mainUrl' => $this->generateMainUrl($url, $options),
            'icon' => $options['icon'],
            'component' => $options['component'],
            'class' => $options['class'],
            'active' => $this->isActive($options),
            'info' => $this->translator->trans($options['info'], [], $options['translation_domain']),
            'notification' => [
                'class' => $options['notification_class'],
                'label' => $this->translator->trans($options['notification_label'], [], $options['translation_domain']),
                'icon' => $options['notification_icon'],
                'info' => $this->translator->trans($options['notification_info'], [], $options['translation_domain']),
            ],
        ];

        $parentData = parent::createViewData($options);
        $data = array_merge($parentData, $data);
        return $data;
    }

    private function generateMainUrl($url, $options)
    {
        $state = StateEncoder::encode([
            'views' => [['url' => $url, 'id' => 2]],
            'storage' => [['key' => 'menu-active-key', 'value' => $options['key']]]
        ]);

        return $this->router->generate('enhavo_app_index', [
            'state' => $state
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'menu-item',
            'translation_domain' => null,
            'icon' => null,
            'class' => null,
            'info' => null,
            'notification_class' => null,
            'notification_label' => null,
            'notification_icon' => null,
            'notification_info' => null,
            'route_parameters' => [],
        ]);

        $resolver->setRequired([
            'label',
            'route'
        ]);
    }

    public function getType()
    {
        return 'base';
    }
}
