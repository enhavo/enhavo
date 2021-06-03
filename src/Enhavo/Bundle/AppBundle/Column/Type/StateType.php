<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $value = $this->getProperty($resource, $options['property']);

        $stateMap = $options['states'];
        $translator = $this->container->get('translator');

        if (isset($stateMap[$value])) {
            $color = isset($stateMap[$value]['color']) ? $stateMap[$value]['color'] : '';
            $label = isset($stateMap[$value]['label']) ? $stateMap[$value]['label'] : '';
            $translationDomain = isset($stateMap[$value]['translation_domain']) ? $stateMap[$value]['translation_domain'] : $options['translation_domain'];

            return [
                'value' => $translator->trans($label, [], $translationDomain),
                'color' => $color
            ];
        }

        return [
            'value' => $value,
            'color' => ''
        ];
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'property' => $options['property'],
            'sortingProperty' => ($options['sortingProperty'] ? $options['sortingProperty'] : $options['property'])
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-state',
            'states' => [],
            'sortingProperty' => null,
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'state';
    }
}
