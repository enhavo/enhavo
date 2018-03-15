<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.03.18
 * Time: 17:52
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\GridBundle\Item\ItemTypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicFormType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ItemTypeResolver
     */
    protected $resolver;

//    public function __construct(ObjectManager $manager, ItemTypeResolver $resolver)
//    {
//        $this->resolver = $resolver;
//        $this->manager = $manager;
//    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
//        $items = array();
//        if(count($options['items'])) {
//            foreach($options['items'] as $item) {
//                $definition = $this->resolver->getDefinition($item);
//                $items[] = array(
//                    'type' => $definition->getName(),
//                    'label' => $definition->getLabel(),
//                    'translationDomain' => $definition->getTranslationDomain()
//                );
//            }
//        } else {
//            foreach($this->resolver->getItems() as $item) {
//                $items[] = array(
//                    'type' => $item->getName(),
//                    'label' => $item->getLabel(),
//                    'translationDomain' => $item->getTranslationDomain()
//                );
//            }
//        }
//        $view->vars['items'] = $items;
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'enhavo_dynamic_form';
    }

    public function getName()
    {
        return 'enhavo_dynamic_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'enhavo_grid_item',
            'allow_delete' => true,
            'allow_add'    => true,
            'by_reference' => false
        ]);
    }
}