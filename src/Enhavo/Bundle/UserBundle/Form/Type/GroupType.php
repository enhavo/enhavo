<?php
/**
 * GroupType.php
 *
 * @since 21/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Security\Roles\AdminRolesProvider;
use Enhavo\Bundle\AppBundle\Security\Roles\RolesProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class GroupType extends AbstractType
{
    protected $roles;

    public function __construct(AdminRolesProvider $rolesProvider)
    {
        $roles = array_keys($rolesProvider->getRoles());

        foreach ($roles as $role) {
            $this->roles[$role] = (sprintf('role.label.%s', $role));
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'group.form.label.name',
            'translation_domain' => 'EnhavoUserBundle'

        ));

        $builder->add('roles', 'choice', array(
            'label' => 'group.form.label.roles',
            'translation_domain' => 'EnhavoUserBundle',
            'choices' => $this->roles,
            'multiple' => true,
            'expanded' => true
        ));
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'enhavo/UserBundle/Entity/Group'
        );
    }

    public function getName()
    {
        return 'enhavo_user_group';
    }
}