<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private VueForm $vueForm
    )
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $this->getVueData($view)->set('itemComponent', $options['item_component']);
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['border'] = $view->vars['border'];
        $data['sortable'] = $view->vars['sortable'];
        $data['allowDelete'] = $view->vars['allow_delete'];
        $data['allowAdd'] = $view->vars['allow_add'];
        $data['blockName'] = $view->vars['block_name'];
        $data['prototype'] = isset($view->vars['prototype']) ? $this->vueForm->createData($view->vars['prototype']) : null;
        $data['prototypeName'] = $view->vars['prototype_name'];
        $data['index'] = null;
        $data['draggableGroup'] = $view->vars['draggable_group'];
        $data['draggableHandle'] = $view->vars['draggable_handle'];
        $data['onDelete'] = null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-list',
            'component_model' => 'ListForm',
            'item_component' => 'form-list-item',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ListType::class];
    }
}
