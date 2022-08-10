<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 19:44
 */

namespace Enhavo\Bundle\AppBundle\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormBatchType extends AbstractBatchType
{
    public function createViewData(array $options, ViewData $data, ?ResourceInterface $resource = null)
    {
        $data['modal'] = [
            'component' => 'ajax-form-modal',
            'route' => $options['form_route'],
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'batch-form'
        ]);

        $resolver->setRequired(['form_route']);
    }
}
