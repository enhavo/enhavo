<?php

namespace Enhavo\Bundle\ResourceBundle\Form\Describer;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Enhavo\Bundle\ResourceBundle\Form\FormDescriberInterface;
use Enhavo\Bundle\ResourceBundle\Form\FormTypeDescriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class CollectionTypeDescriber implements FormTypeDescriberInterface
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private FormDescriberInterface $formDescriber,
    )
    {
    }

    public function describe($options, FormTypeInterface $form, Schema $schema)
    {
        $entryType = $options['entry_type'];
        $entryOptions = $options['entry_options'];

        $schemaName = str_replace('\\', '', $entryType);
        if (!$schema->getDocumentation()->components()->hasSchema($schemaName)) {
            $entryForm = $this->formFactory->create($entryType, null, $entryOptions);
            $formSchema = $schema->getDocumentation()->components()->schema($schemaName);
            $this->formDescriber->describe($entryForm, $formSchema);
        }

        $schema->array()
            ->items()
                ->ref(sprintf('#/components/schemas/%s', $schemaName));
    }

    public static function getFormTypes(): array
    {
        return [CollectionType::class];
    }
}
