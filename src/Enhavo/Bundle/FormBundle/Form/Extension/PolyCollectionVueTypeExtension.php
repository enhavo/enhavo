<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PolyCollectionVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private VueForm $vueForm,
        private PrototypeManager $prototypeManager,
    )
    {
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['allowDelete'] = $view->vars['allow_delete'];
        $data['allowAdd'] = $view->vars['allow_add'];
        $data['entryLabels'] = $view->vars['entry_labels'];
        $data['sortable'] = true;
        $data['index'] = null;

        $data['prototypeStorage'] = $view->vars['poly_collection_config']['prototypeStorage'];
        $data['collapsed'] = $view->vars['poly_collection_config']['collapsed'];
        $data['confirmDelete'] = $view->vars['poly_collection_config']['confirmDelete'];

        $data['itemComponent'] = $options['item_component'];

        $data->addNormalizer(function(FormView $view, VueData $data)
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
        });
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-poly-collection',
            'component_model' => 'PolyCollectionForm',
            'item_component' => 'form-poly-collection-item',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [PolyCollectionType::class];
    }
}
