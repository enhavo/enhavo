<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 13:56
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAutoCompleteEntityType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * UserAutoCompleteEntityType constructor.
     *
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->dataClass,
            'route' => 'enhavo_user_admin_api_user_auto_complete',
            'label' => 'user.label.user',
            'translation_domain' => 'EnhavoUserBundle',
        ]);
    }

    public function getParent()
    {
        return AutoCompleteEntityType::class;
    }
}
