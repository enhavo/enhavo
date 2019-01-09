<?php
/**
 * MagazineType.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\ListType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MagazineType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);

        $border = null;
        $sortable = null;
        $allowDelete = null;
        $allowAdd = null;
        
        $case = 7;
        
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
        
        $builder->add('toc', ListType::class, [
            'type' => 'enhavo_project_content',
            'border' => $border,
            'sortable' => $sortable,
            'allow_delete' => $allowDelete,
            'allow_add' => $allowAdd
        ]);

        $builder->add('cover', MediaType::class, [
            'multiple' => false,
            'formats' => [
                'magazine_cover' => 'Magazin Cover'
            ]
        ]);

        $builder->add('pictures', MediaType::class);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_project_magazine';
    }
}