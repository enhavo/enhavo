<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-18
 * Time: 17:43
 */

namespace Enhavo\Bundle\AppBundle\Menu\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DropdownMenu extends AbstractMenu
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RouterInterface
     */
    protected $router;

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
        $value = $this->getValue($options);
        $choices = $this->getChoices($options);

        $data = [
            'info' => $this->translator->trans($options['info'], [], $options['translation_domain']),
            'choices' => $this->formatChoices($choices, $options['translation_domain']),
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain']),
            'value' => $value,
            'event' => $options['event'],
            'selectedValue' => $this->getInitialValue($value, $choices, $options['translation_domain']),
        ];

        $parentData = parent::createViewData($options);
        $data = array_merge($parentData, $data);
        return $data;
    }

    private function formatChoices(array $choices, $translationDomain)
    {
        $data = [];
        foreach($choices as $value => $label) {
            $data[] = [
                'label' => $this->translator->trans($label, [], $translationDomain),
                'code' => $value,
            ];
        }
        return $data;
    }

    private function getInitialValue($value, array $choices, $translationDomain)
    {
        foreach($choices as $code => $label) {
            if ($value == $code) {
                return [
                    'label' => $this->translator->trans($label, [], $translationDomain),
                    'code' => $value,
                ];
            }
        }
        return null;
    }

    protected function getChoices($options)
    {
        return $options['choices'];
    }

    protected function getValue(array $options)
    {
        return $options['value'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => '',
            'translation_domain' => null,
            'class' => '',
            'component' => 'menu-dropdown',
            'info' => null,
            'value' => null,
        ]);

        $resolver->remove(['icon']);
        $resolver->setRequired([
            'choices',
            'event'
        ]);
    }

    public function getType()
    {
        return 'dropdown';
    }
}
