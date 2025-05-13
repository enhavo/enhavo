<?php

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Security\Roles\RolesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author blutze-media
 */
class PermissionType extends AbstractType
{
    public function __construct(
        private RolesProvider $rolesProvider,
    ) {}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->rolesProvider->getRoles(),
            'multiple' => true,
            'expanded' => true,
            'list' => true,
            'component' => 'form-user-group-permission'
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $choices = $view->vars['choices'];
        $groups = [];
        $lastPrefix = '';
        /** @var ChoiceView $choice */
        foreach ($choices as $index => $choice) {
            $lastIndex = strripos($choice->value, '_');
            $prefix = substr($choice->value, 0, $lastIndex);

            if (!isset($groups[$prefix]) && $lastPrefix != $prefix) {
                $lastPrefix = $prefix;
                $groups[$lastPrefix] = [];
            }
            $groups[$lastPrefix][] = $index;
        }
        $view->vars['groups'] = $groups;
        $view->vars['vue_data']['groups'] = $groups;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
