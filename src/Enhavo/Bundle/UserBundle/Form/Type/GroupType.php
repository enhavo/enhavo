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
    /**
     * @var AdminRolesProvider
     */
    protected $rolesProvider;

    /**
     * GroupType constructor.
     *
     * @param AdminRolesProvider $rolesProvider
     */
    public function __construct(AdminRolesProvider $rolesProvider)
    {
        $this->rolesProvider = $rolesProvider;
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
            'choices' => $this->rolesProvider->getRoles(),
            'multiple' => true,
            'expanded' => true,
            'list' => true
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