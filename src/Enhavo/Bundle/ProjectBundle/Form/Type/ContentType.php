<?php
/**
 * ContentType.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\ListType;
use Enhavo\Bundle\ProjectBundle\Entity\Content;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('teaser', TextType::class);

        $border = null;
        $sortable = null;
        $allowDelete = null;
        $allowAdd = null;

        $case = 0;

        switch($case) {
            case(0):
                $border = false;
                $sortable = false;
                $allowDelete = false;
                $allowAdd = true;
                break;
            case(1):
                $border = true;
                $sortable = false;
                $allowDelete = false;
                $allowAdd = true;
                break;
            case(2):
                $border = false;
                $sortable = true;
                $allowDelete = false;
                $allowAdd = true;
                break;
            case(3):
                $border = true;
                $sortable = true;
                $allowDelete = false;
                $allowAdd = true;
                break;
            case(4):
                $border = false;
                $sortable = false;
                $allowDelete = true;
                $allowAdd = true;
                break;
            case(5):
                $border = true;
                $sortable = false;
                $allowDelete = true;
                $allowAdd = true;
                break;
            case(6):
                $border = false;
                $sortable = true;
                $allowDelete = true;
                $allowAdd = true;
                break;
            case(7):
                $border = true;
                $sortable = true;
                $allowDelete = true;
                $allowAdd = true;
                break;
            case(8):
                $border = false;
                $sortable = false;
                $allowDelete = false;
                $allowAdd = false;
                break;
        }


        $builder->add('tags', ListType::class, [
            'type' => 'text',
            'border' => $border,
            'sortable' => $sortable,
            'allow_delete' => $allowDelete,
            'allow_add' => $allowAdd
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Content::class
        ]);
    }

    public function getName()
    {
        return 'enhavo_project_content';
    }
}