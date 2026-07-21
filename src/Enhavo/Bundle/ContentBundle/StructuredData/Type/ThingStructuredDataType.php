<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData\Type;

use Enhavo\Bundle\ContentBundle\StructuredData\AbstractStructuredDataType;
use Enhavo\Bundle\ContentBundle\StructuredData\Context;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataBag;
use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformer;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * StructuredDataType representing https://schema.org/Thing
 *
 */
class ThingStructuredDataType extends AbstractStructuredDataType
{
    private static array $properties = [
        null,
        '@id',
        'additionalType',
        'alternateName',
        'description',
        'disambiguatingDescription',
        'identifier',
        'image',
        'mainEntityOfPage',
        'name',
        'potentialAction',
        'sameAs',
        'subjectOf',
        'url',
    ];

    public function __construct(
        private readonly StructuredDataTransformer $transformer,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    )
    {
    }

    public function buildData(array $options, object $model, StructuredDataBag $bag, Context $context): void
    {
        if ($options['condition'] !== null) {
            $result = $this->expressionLanguage->evaluate('expr:' . $options['condition'], [
                'options' => $options,
                'model' => $model,
                'bag' => $bag,
                'context' => $context,
            ]);
            if (!$result) {
                return;
            }
        }

        if ($context->isClassAttribute()) {
            if ($options['append_type']) {
                // must be created, so other mappings can apply on it, even if it won't be used
                $data = $bag->createType($context->getTypeName(), false);

                if (!$options['strict'] && !$bag->hasType($options['append_type'])) {
                    // type not exists, but we are not strict, so do nothing
                    return;
                }

                $appendToType = $bag->getType($options['append_type']);

                if ($appendToType->has($options['append_property']) && !$options['overwrite']) {
                    // do not overwrite
                    return;
                }

                if ($appendToType->has($options['append_property']) && $options['append']) {
                    $oldValue = $appendToType->get($options['append_property']);
                    if (!is_array($oldValue)) {
                        $oldValue = [$oldValue];
                    }
                    $oldValue[] = $data;
                    $data = $oldValue;
                }

                $appendToType->set($options['append_property'], $data);
            } else {
                $bag->createType($context->getTypeName());
            }
        } else {
            if (!$options['strict'] && !$bag->hasType($context->getTypeName())) {
                // type not exists, but we are not strict, so do nothing
                return;
            }

            $data = $bag->getType($context->getTypeName());
            $value = $context->getPropertyValue();

            if (empty($value)) {
                return;
            }

            if ($options['transform']) {
                $value = $this->transformer->transform($options['transform'], $value, $options['transform_options']);
            }

            if (empty($value)) {
                return;
            }

            if ($data->has($options['property']) && $options['append']) {
                $oldValue = $data->get($options['property']);
                if (!is_array($oldValue)) {
                    $oldValue = [$oldValue];
                }
                $oldValue[] = $value;
                $value = $oldValue;
            } else if ($data->has($options['property']) && !$options['overwrite']) {
                // do not overwrite
                return;
            }

            $data->set($options['property'], $value);
        }
    }

    public function getTypeName(array $options): ?string
    {
        return 'Thing';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'property' => null,
            'transform' => null,
            'transform_options' => [],
            'overwrite' => true,
            'append' => false,
            'strict' => true,
            'append_type' => null,
            'append_property' => null,
            'condition' => null,
        ]);

        $resolver->setNormalizer('append_type', function ($options, $value) {
            if ($value !== null && null === $options['append_property']) {
                throw new InvalidOptionsException('If "append_type" is set, "append_property" must also be provided');
            }

            return $value;
        });

        $resolver->addAllowedValues('property', self::$properties);
    }

    public static function getName(): ?string
    {
        return 'thing';
    }
}
