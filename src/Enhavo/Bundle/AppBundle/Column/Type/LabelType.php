<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 04/09/16
 * Time: 10:43
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        $label = $this->getProperty($resource, $options['property']);

        $translationDomain = null;
        if($options['translationDomain']) {
            $translationDomain = $options['translationDomain'];
        }

        return $this->container->get('translator')->trans($label, [], $translationDomain);
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [

        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-text'
        ]);
        $resolver->setRequired(['property']);
    }

    public function getType()
    {
        return 'label';
    }
}
