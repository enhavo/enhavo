<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.06.16
 * Time: 10:29
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexData;
use Enhavo\Bundle\SearchBundle\Index\IndexDataBuilder;
use Enhavo\Bundle\SearchBundle\Index\IndexTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;


class HtmlIndexType extends AbstractIndexType implements IndexTypeInterface
{
    private PropertyAccessor $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);

        if ($value === null) {
            return;
        }

        $tagWeights = $options['weights'];

        $tags = array_keys($tagWeights);

        // strip off all ignored tags, insert space before and after them to keep word boundaries.
        $value = str_replace(array('<', '>'), array(' <', '> '), $value);
        $value = strip_tags($value, '<' . implode('><', $tags) . '>');

        // split html tags from plain text.
        $split = preg_split('/\s*<([^>]+?)>\s*/', $value, -1, PREG_SPLIT_DELIM_CAPTURE);

        $tag = false; // odd/even counter. Tag or no tag.
        $tagName = null;
        $tagStack = array();
        foreach ($split as $tagValue) {
            // if tag is true we are handling the tags in the array, if tag is false we are handling text between the tags
            if ($tag) {
                list($tagName) = explode(' ', $tagValue, 2);
                $tagName = strtolower($tagName);

                // Closing or opening tag?
                if (substr($tagName, 0,1) == '/') {
                    array_pop($tagStack);
                } else {
                    $tagStack[] = $tagName;
                }
            } else {
                if ($tagValue != '') {
                    $currentTag = null;
                    if (count($tagStack)) {
                        $currentTag = $tagStack[count($tagStack) - 1];
                    }
                    $index = new IndexData(trim($tagValue), $tagWeights[$currentTag] ?? 1);
                    $builder->addIndex($index);
                }
            }
            $tag = !$tag;
        }
    }

    public function buildRawData(array $options, $model, IndexDataBuilder $builder): void
    {
        $value = $this->propertyAccessor->getValue($model, $options['property']);

        if ($value === null) {
            return;
        }

        $value = strip_tags($value);
        $value = preg_split('/\s+/', $value);
        $value = implode(' ', $value);
        $value = trim($value);

        $builder->addRawData($value);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('property');

        $resolver->setDefaults([
            'weights' => []
        ]);

        $resolver->setNormalizer('weights', function ($options, $value) {
            return array_merge([
                'h1' => 25,
                'h2' => 18,
                'h3' => 15,
                'h4' => 14,
                'h5' => 9,
                'h6' => 6,
                'u' => 3,
                'b' => 3,
                'i' => 3,
                'strong' => 3,
                'em' => 3,
                'a' => 10,
                'p' => 1
            ], $value);
        });
    }

    public static function getName(): ?string
    {
        return 'html';
    }
}
