<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-27
 * Time: 08:33
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem\Type;

use Enhavo\Bundle\NavigationBundle\NavItem\NavItemTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NavItemType extends AbstractType implements NavItemTypeInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    public function getModel($options)
    {
        return $options['model'];
    }

    public function getForm($options)
    {
        return $options['form'];
    }

    public function getFormOptions($options)
    {
        return $options['form_options'];
    }

    public function getLabel(array $options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    public function getTranslationDomain(array $options)
    {
        return $options['translation_domain'];
    }

    public function getFactory($options)
    {
        return $options['factory'];
    }

    public function getTemplate($options)
    {
        return $options['template'];
    }

    public function getComponent($options)
    {
        return $options['component'];
    }

    public function render($options)
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => null,
            'template' => null,
            'component' => null,
            'form_options' => [],
        ]);

        $resolver->setRequired([
            'label', 'model', 'factory', 'form'
        ]);
    }
}
