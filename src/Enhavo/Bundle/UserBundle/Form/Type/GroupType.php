<?php
/**
 * GroupType.php
 *
 * @since 21/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Security\Roles\AdminRolesProvider;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractResourceType
{
    /**
     * @var AdminRolesProvider
     */
    private $rolesProvider;

    /**
     * GroupType constructor.
     *
     * @param string $dataClass
     * @param array $validationGroups
     * @param AdminRolesProvider $rolesProvider
     */
    public function __construct(string $dataClass, array $validationGroups = [], AdminRolesProvider $rolesProvider)
    {
        parent::__construct($dataClass, $validationGroups);
        $this->rolesProvider = $rolesProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, array(
            'label' => 'group.form.label.name',
            'translation_domain' => 'EnhavoUserBundle'

        ));

        $builder->add('roles', ChoiceType::class, array(
            'label' => 'group.form.label.roles',
            'translation_domain' => 'EnhavoUserBundle',
            'choices' => $this->rolesProvider->getRoles(),
            'multiple' => true,
            'expanded' => true,
            'list' => true
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_user_group';
    }
}