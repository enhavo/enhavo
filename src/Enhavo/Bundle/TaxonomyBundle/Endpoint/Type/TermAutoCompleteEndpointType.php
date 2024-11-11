<?php

namespace Enhavo\Bundle\TaxonomyBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\FormBundle\Endpoint\AutoCompleteEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermAutoCompleteEndpointType extends AbstractEndpointType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'repository_method' => 'findByTaxonomyTerm',
            'resource' => 'enhavo_taxonomy.term',
            'choice_label' => 'name',
        ]);

        $resolver->setRequired('taxonomy');

        $resolver->setNormalizer('repository_arguments', function ($options, $value) {
            return [
                'expr:request.get("q")',
                $options['taxonomy'],
                $options['limit']
            ];
        });
    }

    public static function getParentType(): ?string
    {
        return AutoCompleteEndpointType::class;
    }

    public static function getName(): ?string
    {
        return 'term_auto_complete';
    }
}
