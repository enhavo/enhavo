<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 04/09/16
 * Time: 10:43
 */

namespace Enhavo\Bundle\ResourceBundle\Column\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Contracts\Translation\TranslatorInterface;

class LabelColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        $propertyAccessor= new PropertyAccessor;
        $value = $propertyAccessor->getValue($resource, $options['property']);
        $data->set('value', $this->translator->trans($value, [], $options['translation_domain']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
        ]);
        $resolver->setRequired(['property']);
    }

    public static function getName(): ?string
    {
        return 'label';
    }
}
