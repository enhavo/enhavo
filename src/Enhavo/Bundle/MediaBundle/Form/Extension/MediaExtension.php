<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.09.17
 * Time: 16:24
 */

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaExtension extends AbstractTypeExtension
{
    /**
     * @var array
     */
    private $formConfiguration;

    public function __construct($formConfiguration)
    {
        $this->formConfiguration = $formConfiguration;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $options = [];
        if($this->formConfiguration['parameters_type']) {
            $options['entry_options'] = [];
            $options['entry_options']['parameters_type'] = $this->formConfiguration['parameters_type'];
        }

        $resolver->setDefaults($options);
    }

    public static function getExtendedTypes(): iterable
    {
        return [MediaType::class];
    }
}
