<?php

namespace Enhavo\Bundle\FormBundle\Form\VueType;

use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormView;

class PolyCollectionVueType extends AbstractVueType
{
    public function __construct(
        private VueForm $vueForm,
        private PrototypeManager $prototypeManager,
    )
    {
    }

    public static function supports(FormView $formView): bool
    {
        return in_array('enhavo_poly_collection', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['allowDelete'] = $view->vars['allow_delete'];
        $data['allowAdd'] = $view->vars['allow_add'];
        $data['entryLabels'] = $view->vars['entry_labels'];
        $data['sortable'] = true;
        $data['index'] = null;


        $data['prototypeStorage'] = $view->vars['poly_collection_config']['prototypeStorage'];
        $data['collapsed'] = $view->vars['poly_collection_config']['collapsed'];
        $data['confirmDelete'] = $view->vars['poly_collection_config']['confirmDelete'];

        $data['component'] = 'form-poly-collection';
        $data['componentModel'] = 'PolyCollectionForm';

        $data['itemComponent'] = 'form-poly-collection-item';
    }

    public function finishView(FormView $view, VueData $data)
    {
        $storage = $view->vars['poly_collection_config']['prototypeStorage'];
        $prototypes = $this->prototypeManager->getPrototypes($storage);

        $root = $this->getRoot($data);

        if (!$root->has('prototypes')) {
            $root->set('prototypes', []);
        }

        $array = $root->get('prototypes');

        foreach ($prototypes as $prototype) {
            $array[] = [
                'name' => $prototype->getName(),
                'storageName' => $prototype->getStorageName(),
                'parameters' => $prototype->getParameters(),
                'form' => $this->vueForm->createData($prototype->getForm()->createView()),
            ];
        }

        $root->set('prototypes', $array);
    }

    private function getRoot(VueData $data): VueData
    {
        $parent = $data;
        while ($parent != null)
        {
            $data = $parent;
            $parent = $data->getParent();
        }

        return $data;
    }
}
