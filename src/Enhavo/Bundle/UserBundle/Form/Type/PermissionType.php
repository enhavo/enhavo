<?php
/**
 * @author blutze-media
 * @since 2022-05-12
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Security\Roles\RolesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                $groups[$lastPrefix] = [
                    $index => $choice,
                ];
            } else {
                $groups[$lastPrefix][$index] = $choice;
            }
        }
        $view->vars['groups'] = $groups;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_permission';
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
