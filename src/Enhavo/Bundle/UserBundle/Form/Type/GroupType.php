<?php
/**
 * GroupType.php
 *
 * @since 21/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, array(
            'label' => 'group.form.label.name',
            'translation_domain' => 'EnhavoUserBundle'

        ));

        $builder->add('roles', PermissionType::class, array(
            'label' => 'group.form.label.roles',
            'translation_domain' => 'EnhavoUserBundle',
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_group';
    }
}
